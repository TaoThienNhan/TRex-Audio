# Hướng dẫn cài đặt mã nguồn WordPress - Tây Nam Solutions

## Mở đầu

Mã nguồn phải được cài đặt theo trình tự bên dưới để tránh lỗi và dễ dàng bảo trì về sau.

## Clone mã nguồn

```
https://gitlab.com/lhmnhut.taynamsolutions/tnswp
```

## Cài đặt Node / NPM

```
https://nodejs.org/en/
```

## Cài đặt Gulp CLI

```
https://gulpjs.com/
```

## Cài đặt WordPress

Download và giải nén phiên bản WordPress mới nhất.

```
https://wordpress.org/download/
```

Xoá thư mục wp-content/themes, sao chép toàn bộ mã nguồn WordPress vào thư mục dự án.

## Cấu trúc theme

Dự án sẽ được lập trình tại thư mục wp-content/tns-child, cấu trúc thư mục như sau:

### sass

Chứa các file style của dự án (_*.scss).

### js

Chứa các file script của dự án.

### images

Chứa các file hình ảnh của dự án.

### libs

Chứa các thư viện bên thứ ba.

### inc

Chứa các file chức năng (php) của dự án.

Ngoài các file mặc định, các bạn lập trình viên có thể tạo thêm các file tuỳ vào
yêu cầu dự án. Sau đó include vào file functions.php.

### template-parts

Chứa các file template của dự án.

### functions.php

Dùng để include các file chức năng trong thư mục inc.

### inc/init.php

Khởi tạo các chức năng cơ bản của dự án như: nhúng js, css,...

### inc/layouts.php

Hiển thị các file template trong thư mục template-parts vào đúng vị trí được thiết kế.

## Quy tắt viết commit message

Cài đặt 1 plugin mới:

```
[plugin] - install {plugin name}
```

Ví dụ: bạn vừa cài đặt plugin Classic Editor

```
[plugin] - install Classic Editor
```

Lập trình xong 1 tempalte

```
[theme] - {tempalte} - {tempalte-part} - {message}
```

Ví dụ: Bạn vừa responsive xong section slider ở trang chủ

```
[theme] - home - slider - responsive
```

## [Update] 17/02/2022

Sử dụng Gulp để nhúng thư viện củng như tối ưu hoá js, css.

## Lưu ý

Nếu dự án không có các thư mục bên trên thì các bạn tự tạo và không được tự ý sửa tên.

Viết code sạch, gọn gàng, comment code đầy đủ.

Khi file mới, code xong 1 tính năng,... thì phải commit, viết message gọn gàn, dễ hiểu.

Không được tự ý cài đặt plugin, mọi plugin cài đặt vào dự án phải được thông qua.
