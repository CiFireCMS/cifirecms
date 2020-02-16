# Changelog CiFireCMS

### Build Version 2.0.0 LTS Beta Final (16 February 2020) :
- Perbaikan skrip

### Build Version 2.0.0 LTS Beta (7 January 2020) :
- Perubahan nama direktori (l-app, l-content, l-system)
- Pergantian sistem bahasa ke standar codeigniter.
- Modifikasi CI System ``system/core/Lang.php`` line ``197``
- Penghapusan fitur panel member.
- Perubahan core controller backend menjadi Backend_Controller.
- Perubahan setiap extends class component ke Backend_Controller.
- Pergantian UI backend ke versi LTS (standard Bacend UI version 2.x).
- Perubahan UI tema sovi menggunakan tema standar bootstrap.
- Pembaharuan sistem templating pada frontend.
- Penghapusan sistem user level diganti dengan user group.
- Perubahan component Setting.
- Penambahan component Permissions.
- Pembaharuan sistem role permission.
- Penambahan route permissions.
- Pembaharuan sistem web route, bisa di ubah pada file index.php 
- Penamaan helper dan libraries cifire.
- Pembaharuan config filemanager disesuaikan dengan role permission.
- Mengaktifkan fitur ``USE_ACCESS_KEYS`` pada plugin File Manager.
- Pergantian controller Login manjadi Auth.
- Penghapusan tabel database ``t_language``.
- Penghapusan tabel database ``t_user_level``.
- Penghapusan tabel database ``t_user_role``.
- Penambahan tabel database ``t_user_group``.
- Penambahan tabel database ``t_roles``.
- Penambahan tabel database ``t_timezone``.


### Build Version 1.1.0 (10 October 2019) :
- Upgrade core CI ke versi 3.1.11
- Perubahan route error 404 ke controller Error_404.
- Pergantian theme sovi.
- Penambahan mod profile pada halaman administrator.
- Penambahan website images pada setting picture.
- Penambahan setingan ON/OFF visitors.
- Penambahan setingan ON/OFF captcha.
- Perbaikan engine dynamic menu.
- Perbaikan engine pagination.
- Perbaikan engine CompoGen.
- Perbaikan keseluruhan code program pada halaman member. 
- Perbaikan program pengiriman email register dan forgot password.
- Minify script plugins.
- Perubahan logic dinamic routes
- Update submit setting slug.
- Fixed other bugs.


### Build Version 1.0.0 (1 October 2019) :
- Release with standard feature.