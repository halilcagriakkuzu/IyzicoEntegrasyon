## Checkout Task

Laravel v5.8.19 sürümü kullanılmıştır.
Bootstrap ve jQuery kullanılmıştır.

- $ `composer install` komutu ile gerekli back-end paketler kurulmalı.
- $ `npm install` komutu ile gerekli front-end paketler kurulmalı.

#### Linux/Unix:
- $ `cp .env.example .env` komutu ile bir .env dosyası oluşturulmalı.
#### Windows:
- $ `copy .env.example .env` komutu ile bir .env dosyası oluşturulmalı.

- .env dosyası içerisindeki APIKEY ve APISECRET kısmı Iyzico deneme hesabından alınıp eklenmeli.
- .env dosyası içerisinde database alanları ayarlanmalı ve bir database oluşturulmalı.
- $ `php artisan key:generate` komutu ile laravel key'i oluşturulmalı.
- $ `php artisan migrate:fresh --seed` komutu ile oluşturulan database içerisine tablolar oluşturulacaktır. Ürün kayıtları seeder ile eklenecektir.
- $ `npm run development` komutu ile Laravel Mix çalıştırılarak front-end için gerekli dependency'leri oluşturacak. (jQuery - Bootstrap vb.)
- $ `php artisan serve` ile proje çalıştırılabilir.

### Örnek Bilgiler:
- Normal Kredi Kartı Numarası : 5528790000000008
- %20 İndirimli Kredi Kartı : 4987490000000002
