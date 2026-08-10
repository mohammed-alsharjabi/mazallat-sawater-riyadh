#!/usr/bin/env bash

set -euo pipefail

SOURCE_DIR="${1:?مرر مسار نسخة Git أولًا}"
APP_ROOT="${2:?مرر مسار التطبيق الدائم ثانيًا}"
PUBLIC_TARGET="${3:?مرر مسار public_html الخاص بـ test-node.nicebox-sa.com ثالثًا}"
PUBLIC_TARGET="${PUBLIC_TARGET%/}"
RELEASE_ID="$(date +%Y%m%d%H%M%S)"
RELEASE_DIR="${APP_ROOT}/releases/${RELEASE_ID}"
SHARED_DIR="${APP_ROOT}/shared"

if [[ ! -f "${SOURCE_DIR}/artisan" || ! -d "${SOURCE_DIR}/.git" ]]; then
    echo "المسار الأول ليس نسخة Git صالحة لمشروع Laravel." >&2
    exit 1
fi

case "${PUBLIC_TARGET}" in
    */domains/test-node.nicebox-sa.com/public_html) ;;
    *)
        echo "أُوقف النشر: يجب أن يكون الهدف public_html الخاص بالنطاق test-node.nicebox-sa.com فقط." >&2
        exit 1
        ;;
esac

if [[ "${APP_ROOT}" == "/" || "${APP_ROOT}" == "${HOME}" || "${PUBLIC_TARGET}" == "/" || "${PUBLIC_TARGET}" == "${HOME}" ]]; then
    echo "أُوقف النشر: مسار واسع أو غير آمن." >&2
    exit 1
fi

mkdir -p "${APP_ROOT}/releases" "${SHARED_DIR}"
if [[ ! -f "${SHARED_DIR}/.env" ]]; then
    echo "أنشئ ${SHARED_DIR}/.env من .env.production.example قبل النشر." >&2
    exit 1
fi

mkdir -p "${RELEASE_DIR}"
git -C "${SOURCE_DIR}" archive --format=tar HEAD | tar -xf - -C "${RELEASE_DIR}"

if [[ ! -d "${SHARED_DIR}/storage" ]]; then
    mv "${RELEASE_DIR}/storage" "${SHARED_DIR}/storage"
else
    rm -rf "${RELEASE_DIR}/storage"
fi
ln -s "${SHARED_DIR}/storage" "${RELEASE_DIR}/storage"
ln -s "${SHARED_DIR}/.env" "${RELEASE_DIR}/.env"

composer install --working-dir="${RELEASE_DIR}" --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm --prefix "${RELEASE_DIR}" ci
npm --prefix "${RELEASE_DIR}" run build

php "${RELEASE_DIR}/artisan" migrate --force
php "${RELEASE_DIR}/artisan" storage:link
php "${RELEASE_DIR}/artisan" optimize
php "${RELEASE_DIR}/artisan" queue:restart

mkdir -p "$(dirname "${PUBLIC_TARGET}")"
if [[ -e "${PUBLIC_TARGET}" || -L "${PUBLIC_TARGET}" ]]; then
    BACKUP_TARGET="${PUBLIC_TARGET}.backup-${RELEASE_ID}"
    mv "${PUBLIC_TARGET}" "${BACKUP_TARGET}"
    echo "نُقلت النسخة السابقة إلى ${BACKUP_TARGET}"
fi

ln -s "${RELEASE_DIR}/public" "${PUBLIC_TARGET}"
ln -sfn "${RELEASE_DIR}" "${APP_ROOT}/current"

echo "اكتمل النشر في ${PUBLIC_TARGET} من الإصدار ${RELEASE_ID}."
