<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Hakkında</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* yatay ortala */
            align-items: center;    /* dikey ortala */
            height: 100vh;          /* ekran yüksekliği kadar */
        }
        .container {
            background-color: #ffffffdd; /* yarı saydam beyaz arkaplan */
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            max-width: 700px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        p {
            color: #555;
            line-height: 1.6;
            font-size: 18px;
            margin-bottom: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        ul li {
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Proje Hakkında</h1>
        <p>
            Bu proje, öğrenci ve öğretim üyelerinin ders notlarını kolayca paylaşabilmesi ve yönetebilmesi amacıyla geliştirilmiştir. 
            Kullanıcılar kendi notlarını yükleyip düzenleyebilir, diğer kullanıcıların paylaştığı notları görüntüleyebilir.
            Projede kullanıcı rolleri (admin ve normal kullanıcı) ile güvenlik ve yetkilendirme sağlanmıştır.
        </p>
        <h2>Projeyi Geliştirenler</h2>
        <ul>
            <li>Ahsen Bülbül</li>
            <li>İrem Teker</li>
            <li>Nazile Afra Görgen</li>
        </ul>
    </div>
</body>
</html>

