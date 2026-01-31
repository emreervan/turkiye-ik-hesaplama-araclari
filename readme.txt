=== Türkiye İK Hesaplama Araçları ===
Contributors: emreervan
Tags: bordro, maaş, zam, gelir vergisi, yıllık izin, hesaplama, türkiye, payroll, salary
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Türkiye mevzuatına uygun bordro, zam, gelir vergisi ve yıllık izin hesaplama araçları. 2026 parametreleri ile güncel.

== Description ==

Türkiye İK Hesaplama Araçları, insan kaynakları departmanları ve çalışanlar için geliştirilmiş kapsamlı bir hesaplama eklentisidir. 2026 yılı Türkiye mevzuatına tam uyumludur.

= Özellikler =

**Bordro / Maaş Hesaplama**
* Brütten nete hesaplama
* Netten brüte hesaplama
* 12 aylık detaylı bordro çıktısı
* SGK, işsizlik, gelir vergisi ve damga vergisi hesaplaması
* İşveren maliyeti hesaplama
* Emekli çalışan desteği (SGDP)
* Hazine desteği seçenekleri (%5, %2, yok)
* Asgari ücret istisnası otomatik uygulaması

**Zam Hesaplama**
* Yüzdeden yeni maaş hesaplama
* Yeni maaştan zam oranı hesaplama
* Artış katı gösterimi

**Gelir Vergisi Hesaplama**
* Artan oranlı vergi hesaplama
* 2025 ve 2026 vergi dilimleri
* Ücretli ve ücret dışı mükellef türleri
* Asgari ücret istisnası seçeneği
* Dilim bazlı detaylı görünüm
* Efektif vergi oranı hesaplama

**Yıllık İzin Hesaplama**
* Kamu sektörü (657 DMK)
* Özel sektör (4857 İş Kanunu)
* Detaylı hesaplama (tarih bazlı)
* Hızlı hesaplama (yıl bazlı)
* Bir sonraki dilime kalan süre gösterimi

= 2026 Parametreleri =

* Asgari Ücret Brüt: 33.030 TL (Ocak-Haziran), 37.455 TL (Temmuz-Aralık)
* SGK Tavan: 330.300 TL
* SGK İşçi: %14, İşsizlik İşçi: %1
* SGK İşveren: %20,5 (Brüt→Net), %21,75 (Net→Brüt), İşsizlik İşveren: %2
* Gelir Vergisi Dilimleri: %15, %20, %27, %35, %40

= Kullanım =

Shortcode'ları herhangi bir sayfa veya yazıya ekleyerek kullanabilirsiniz:

* `[maas_hesapla]` - Bordro hesaplama
* `[zam_hesapla]` - Zam hesaplama
* `[gelir_vergisi_hesapla]` - Gelir vergisi hesaplama
* `[izin_hesapla]` - Yıllık izin hesaplama

== Installation ==

1. Eklenti dosyalarını `/wp-content/plugins/turkiye-ik-hesaplama/` dizinine yükleyin.
2. WordPress yönetim panelinden 'Eklentiler' menüsüne gidin.
3. 'Türkiye İK Hesaplama Araçları' eklentisini etkinleştirin.
4. İstediğiniz sayfaya ilgili shortcode'u ekleyin.

== Frequently Asked Questions ==

= Hangi WordPress sürümlerini destekliyor? =

WordPress 5.8 ve üzeri sürümleri desteklemektedir.

= Hesaplamalar hangi yıla göre yapılıyor? =

Tüm hesaplamalar 2026 yılı Türkiye mevzuatına göre yapılmaktadır. Gelir vergisi hesaplamasında 2025 ve 2026 yılları seçilebilir.

= Emekli çalışanlar için hesaplama yapılabiliyor mu? =

Evet, bordro hesaplamasında "Emekli Çalışan" seçeneği ile SGDP (%24,75) hesaplaması yapılmaktadır.

= Hazine desteği nedir? =

İşveren SGK priminden yapılan indirimdir. %5, %2 veya yok seçenekleri mevcuttur.

== Screenshots ==

1. Bordro hesaplama formu ve sonuçları
2. Zam hesaplama aracı
3. Gelir vergisi hesaplama ve dilim görünümü
4. Yıllık izin hesaplama araçları
5. Admin panel görünümü

== Changelog ==

= 1.0.0 =
* İlk sürüm
* Bordro hesaplama (brütten nete, netten brüte)
* Zam hesaplama
* Gelir vergisi hesaplama
* Yıllık izin hesaplama
* 2026 parametreleri

== Upgrade Notice ==

= 1.0.0 =
İlk sürüm. Türkiye 2026 mevzuatına uygun hesaplamalar.
