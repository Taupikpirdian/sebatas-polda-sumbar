## Issue Has Happened
1. Livewire tidak bekerja, akibat pemanggilan assets yang salah. di versi 1 sepertinya hanya support untuk pemanggilan @livewireScripts dan @livewireStyles
2. Di server terjadi lagi, livewire tidak berfungsi. solusinya publish assetnya php artisan vendor:publish --force --tag=livewire:assets, lalu edit di file config livewire bagian asset_url, dan edit pemanggilan @livewireScripts menjadi <livewire:scripts> lakukan save, lalu ubah lagi menjadi @livewireScripts, entah kenapa jadi work.
3. error 404 ketika mau filter atau klik paginate, karena function mount() hanya di call 1x ketika reload page, jadi var yang di dalam function tsb hanya digunakan 1x

## Tahapan Setup
A. Aplication
1. composer install or update or composer dump-autoload
2. php artisan migrate --seed
B. SSH Git on Hosting
1. Generate terlebih daluhu SSH key di menu "SSH Access"
2. Akan diminta beberapa data termasuk password untuk menggunakan SSHnya
3. Ketika sesudah di generate, SSH harus di Authorization
4. Setiap pull atau push di terminal hosting, harus menggunakan password SSH

## Discution issue
1. **[Save to Storage](https://kodingin.com/cara-upload-file-menggunakan-filesystem-storage-di-laravel/)**