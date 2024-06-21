php artisan make:model TipeKamar

php artisan make:controller Api/AreaController --api

php artisan make:resource AreaResource

https://dev.to/dalelantowork/laravel-8-api-resources-for-beginners-2cpa



# Response
- berhasil 200
- berhasil nambah 201
- berhasil hapus 204
- error validasi 422
- belum login 401
- data ga ketemu 404


### akses docker
docker-compose exec app php artisan storage:link


### TODO

- Kamar -> assign penyewa
- 
