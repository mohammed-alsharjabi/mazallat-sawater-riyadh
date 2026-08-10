# نشر الموقع على جذر `test-node.nicebox-sa.com`

النطاق المستهدف هو `https://test-node.nicebox-sa.com/` مباشرةً دون `/booking`. سكربت النشر يقبل فقط مسارًا ينتهي صراحةً بـ `domains/test-node.nicebox-sa.com/public_html`، وينقل الموقع السابق إلى مجلد احتياطي مؤرخ قبل ربط الإصدار الجديد؛ لذلك يمكن الرجوع عنه بدل حذفه نهائيًا.

## البيانات المطلوبة مرة واحدة

- اسم مستخدم SSH واسم الخادم والمنفذ من hPanel.
- المسار المطلق الموثق لجذر `public_html` الخاص بالنطاق الفرعي.
- قاعدة MySQL واسم المستخدم وكلمة المرور.
- بريد المدير وكلمة مرور أولية طويلة وفريدة.
- رمز Search Console ومعرّف GA4 بعد إنشائهما لصاحب النشاط.

## الإعداد الأول على الخادم

الأمثلة التالية تفترض أن Hostinger أعطى مسارًا مثل `/home/USER/domains/test-node.nicebox-sa.com/public_html`. استبدل `USER` بالقيمة الفعلية فقط بعد التحقق منها:

```bash
git clone https://github.com/mohammed-alsharjabi/mazallat-sawater-riyadh.git /home/USER/mazallat-source
mkdir -p /home/USER/mazallat-app/shared
cp /home/USER/mazallat-source/.env.production.example /home/USER/mazallat-app/shared/.env
chmod 600 /home/USER/mazallat-app/shared/.env
```

عدّل ملف البيئة وأدخل بيانات MySQL، ثم أنشئ `APP_KEY` مرة واحدة فقط:

```bash
cd /home/USER/mazallat-source
php artisan key:generate --show
```

انسخ المفتاح الناتج إلى `APP_KEY` في الملف المشترك. لا ترفعه إلى GitHub.

## النشر الآمن

```bash
cd /home/USER/mazallat-source
git pull --ff-only origin main
bash deploy/hostinger-release.sh \
  /home/USER/mazallat-source \
  /home/USER/mazallat-app \
  /home/USER/domains/test-node.nicebox-sa.com/public_html
```

بعد أول نشر فقط، شغّل Seeder الإطلاق لإنشاء التصنيفات والخدمات والمقالات المسودة وحساب المدير المعرّف في البيئة:

```bash
php /home/USER/mazallat-app/current/artisan db:seed --force
```

ارفع `assets.zip` إلى المسار المشترك التالي، ولا تضعه داخل `public_html`:

```text
/home/USER/mazallat-app/shared/storage/app/assets.zip
```

ثم نفذ الاستيراد. لا يغيّر الأمر أي خدمة من Draft إلى Published:

```bash
php /home/USER/mazallat-app/current/artisan services
```

احتفظ بالملف المضغوط وبـ`shared/storage/app/private/service-images/originals`. يجب أن يدعم PHP إضافات `zip`, `fileinfo`, `gd`, `exif` وWebP؛ AVIF اختياري. إذا استُخدم `--queue` بدل المعالجة المباشرة، شغّل عامل Queue قبل مراجعة الصور في اللوحة.

إذا استُخدم `QUEUE_CONNECTION=database`، أضف Cron كل دقيقة:

```bash
php /home/USER/mazallat-app/current/artisan queue:work --stop-when-empty --tries=3
```

## التحقق بعد النشر

```bash
curl -I https://test-node.nicebox-sa.com/
curl -I https://test-node.nicebox-sa.com/admin/login
curl https://test-node.nicebox-sa.com/robots.txt
curl https://test-node.nicebox-sa.com/sitemap_index.xml
```

راجع أن الروابط والأصول تبدأ من جذر النطاق، وأن `/admin/login` يعرض نموذج الدخول. سيصبح موقع eco+ السابق داخل مجلد `public_html.backup-TIMESTAMP` بجوار `public_html` الجديد. لا تحذف النسخة الاحتياطية إلا بعد اعتماد الموقع والنسخ الاحتياطي لقاعدة البيانات وملفات `storage`.
