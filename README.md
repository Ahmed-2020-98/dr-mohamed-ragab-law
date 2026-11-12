# موقع المستشار محمد رجب المحامي

موقع تعريفي بالعربية (RTL) مبني على **Laravel 13** مع لوحة تحكم **Filament 5** على `/admin`.

## التشغيل محليًا

```bash
composer install
npm install && npm run build
cp .env.example .env && php artisan key:generate
# اضبط ADMIN_EMAIL و ADMIN_PASSWORD في .env
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

- الموقع: http://127.0.0.1:8000
- لوحة التحكم: http://127.0.0.1:8000/admin (بالبريد وكلمة المرور الموجودين في `.env`)

## ما تديره لوحة التحكم

| القسم | المحتوى |
|---|---|
| التواصل | طلبات الاستشارة (حالة: جديد / تم التواصل / مغلق + زر واتساب مباشر) |
| المحتوى | الخدمات، المقالات وتصنيفاتها، الأسئلة الشائعة، الصفحات، آراء العملاء |
| الوسائط | معرض الصور، الفيديوهات (ملف مرفوع أو رابط يوتيوب) |
| الإعدادات | الهوية والشعار، محتوى الرئيسية و«من نحن»، أرقام التواصل والسوشيال، SEO و Google Analytics |

ملاحظات:
- **المقالات الثلاث** موجودة كمسودات — راجعها وفعّل «منشور» لتظهر. رابط «المدونة» في القائمة يظهر تلقائيًا عند نشر أول مقال.
- **آراء العملاء** و**الأرقام** (سنوات الخبرة…) لا تظهر في الموقع إلا بعد إدخال بيانات حقيقية.
- لتصلك رسالة بكل طلب استشارة: ضع «بريد استقبال الطلبات» في إعدادات التواصل واضبط `MAIL_*` في `.env`.

## SEO

- عنوان ووصف و canonical و Open Graph لكل صفحة (قابلة للتعديل لكل خدمة/مقال/صفحة).
- JSON-LD: `LegalService`/`Attorney`، `WebSite`، `BreadcrumbList`، `Service`، `Article`، `FAQPage`، `VideoObject`.
- `sitemap.xml` و `robots.txt` ديناميكيان.
- روابط الموقع القديم: `/works` و `/imported` تحوّل للرئيسية، ومقالات السبام وأرشيفات ووردبريس ترجع **410** حتى يحذفها جوجل.
- بعد الإطلاق: أضف الموقع في Google Search Console وارفع `https://mohamedragablaw.com/sitemap.xml`.

## الرفع على الاستضافة (Hostinger أو غيرها)

1. ارفع المشروع، واجعل **Document root** يشير إلى مجلد `public`.
2. في `.env`: `APP_ENV=production`، `APP_DEBUG=false`، `APP_URL=https://mohamedragablaw.com`، وبيانات MySQL (`DB_CONNECTION=mysql` ...).
3. نفّذ:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan migrate --seed --force
   php artisan storage:link
   php artisan optimize
   ```
4. `npm run build` محليًا وارفع مجلد `public/build` إن لم يتوفر Node على الاستضافة.
5. **ملفات الفيديو** (نحو 80 ميجابايت في `storage/app/public/videos`) غير مضمّنة في Git — انسخها يدويًا إلى نفس المسار على السيرفر قبل `db:seed`، أو ارفعها لاحقًا من لوحة التحكم.

## الاختبارات

```bash
php artisan test
```
