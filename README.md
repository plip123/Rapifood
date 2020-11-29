## Signup
<p>
    http://127.0.0.1:8000/api/signup
    
    {
        'name':'required|string',
        'lastname':'required|string',
        'email':'required|string',
        'roleID':'required|integer',
        'address':'required|string',
        'city':'required|string',
        'password':'required|string'
    }
</p>

## Login
<p>
    http://127.0.0.1:8000/api/login
    
    {
        'email',
        'password',
        'remember_me'
    }
</p>

## Product CRUD
<p>
    http://127.0.0.1:8000/api/product
    method:POST
    
    {
        'name': 'required|string',
        'price': 'required|double',
        'offert_price': 'double',
        'description': 'string',
        'state': 'required|string',
        'offert': 'required|boolean',
        'image': 'required|string',
        'priority': 'required|integer',
        'storeID': 'required|integer'
    }

    http://127.0.0.1:8000/api/product/{id}
    method:GET
    
    http://127.0.0.1:8000/api/product/1
    method:PUT
    {
        'name': 'required|string',
        'price': 'required|double',
        'offert_price': 'double',
        'description': 'string',
        'state': 'required|string',
        'offert': 'required|boolean',
        'image': 'required|string',
        'priority': 'required|integer',
        'storeID': 'required|integer'
    }
    
    http://127.0.0.1:8000/api/product/{id}
    method:DELETE  
</p>

## Payment CRUD
<p>
    http://127.0.0.1:8000/api/payment
    method:POST
    
    {
        'name': 'required|string',
        'description': 'string',
        'apiKey': 'required|string'
    }

    http://127.0.0.1:8000/api/payment/{id}
    method:GET
    
    http://127.0.0.1:8000/api/payment/1
    method:PUT
    {
        'name': 'required|string',
        'description': 'string',
        'apiKey': 'required|string'
    }
    
    http://127.0.0.1:8000/api/payment/{id}
    method:DELETE  
</p>

## Pay
<p>
    http://127.0.0.1:8000/api/pay
    
    {
        'paymentID': 'required|integer',
        'cardNumber': 'required|string',
        'cardDate': 'required|string',
        'securityCode': 'required|string',
        'cardName': 'required|string',
        'amount': 'required|integer',
        'description': 'required|string',
        'ref': 'string'
    }
</p>

## Payment Order
<p>
    http://127.0.0.1:8000/api/order
    method:POST
    
    {
        'name': 'required|string',
        'description': 'string',
        'apiKey': 'required|string'
    }

    http://127.0.0.1:8000/api/order/{id}
    method:GET
    
    http://127.0.0.1:8000/api/order/1
    method:PUT
    {
        'name': 'required|string',
        'description': 'string',
        'apiKey': 'required|string'
    }
    
    http://127.0.0.1:8000/api/order/{id}
    method:DELETE  
</p>



## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)
- [Appoly](https://www.appoly.co.uk)
- [OP.GG](https://op.gg)
- [云软科技](http://www.yunruan.ltd/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
