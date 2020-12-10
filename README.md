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
    Insert
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
        'categoryID' : 'required|integer',
        'ingredientID' : [integer]
    }

    Show
    http://127.0.0.1:8000/api/product/{id}
    method:GET

    Get all
    http://127.0.0.1:8000/api/product/
    method:GET
    
    Update
    http://127.0.0.1:8000/api/product/{id}
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
        'categoryID' : 'required|integer',
        'ingredientID' : [integer]
    }
    
    Delete
    http://127.0.0.1:8000/api/product/{id}
    method:DELETE

    Filter
    http://127.0.0.1:8000/api/productFilter
    method:GET

    {
        'restaurant_id': 'integer',
        'type_id': 'integer',
        'min_price': 'double',
        'max_price': 'double',
        'like': 'string'
    }
</p>

## Extras CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/extra
    method:POST

    {
        'name': 'required|string',
        'image': 'file',
        'price': 'required|double'
    }

    All
    http://127.0.0.1:8000/api/extra/
    method:GET

    Show
    http://127.0.0.1:8000/api/extra/{id}
    method:GET
    
    Update
    http://127.0.0.1:8000/api/extra/{id}
    method:PUT

    {
        'name': 'required|string',
        'image': 'file',
        'price': 'required|double'
    }
    
    Delete
    http://127.0.0.1:8000/api/extra/{id}
    method:DELETE  
</p>

## Store CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/store
    method:POST

    {
        'name': 'required|string',
        'userID': 'required|integer',
        'logo': 'file',
        'address': 'required|string',
        'city': 'required|string'
    }

    All
    http://127.0.0.1:8000/api/store/
    method:GET

    Show
    http://127.0.0.1:8000/api/store/{id}
    method:GET
    
    Update
    http://127.0.0.1:8000/api/store/{id}
    method:PUT

    {
        'name': 'required|string',
        'logo': 'file',
        'address': 'required|string',
        'city': 'required|string'
    }
    
    Delete
    http://127.0.0.1:8000/api/store/{id}
    method:DELETE  
</p>


## Ingredient CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/ingredient
    method:POST

    {
        'name': 'required|string',
    }

    All
    http://127.0.0.1:8000/api/ingredient/
    method:GET

    Show
    http://127.0.0.1:8000/api/ingredient/{id}
    method:GET
    
    Update
    http://127.0.0.1:8000/api/ingredient/{id}
    method:PUT

    {
        'name': 'required|string',
    }
    
    Delete
    http://127.0.0.1:8000/api/ingredient/{id}
    method:DELETE  
</p>


## Role CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/role
    method:POST

    {
        'name': 'required|string',
        'permission_lvl': 'required|integer'
    }

    All
    http://127.0.0.1:8000/api/role/
    method:GET

    Show
    http://127.0.0.1:8000/api/role/{id}
    method:GET
    
    Update
    http://127.0.0.1:8000/api/role/{id}
    method:PUT

    {
        'name': 'required|string',
        'permission_lvl': 'required|integer'
    }
    
    Delete
    http://127.0.0.1:8000/api/role/{id}
    method:DELETE  
</p>


## Notifications CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/notification
    method:POST

    {
        'userID': 'required|integer',
        'message': 'required|string',
        'state': 'required|string'
    }

    All
    http://127.0.0.1:8000/api/notification/
    method:GET

    Show
    http://127.0.0.1:8000/api/notification/{id}
    method:GET
    
    Update
    http://127.0.0.1:8000/api/notification/{id}
    method:PUT

    {
        'userID': 'required|integer',
        'message': 'required|string',
        'state': 'required|string'
    }
    
    Delete
    http://127.0.0.1:8000/api/notification/{id}
    method:DELETE  
</p>


## Favorites CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/favorites
    method:POST

    {
        'userID': 'required|integer',
        'productID': 'required|integer',
        'status': 'required|string'
    }

    All
    http://127.0.0.1:8000/api/favorites/
    method:GET

    Show
    http://127.0.0.1:8000/api/favorites/{id}
    method:GET
    
    Update
    http://127.0.0.1:8000/api/favorites/{id}
    method:PUT

    {
        'userID': 'required|integer',
        'productID': 'required|integer',
        'status': 'required|string'
    }
    
    Delete
    http://127.0.0.1:8000/api/favorites/{id}
    method:DELETE  
</p>


## Category CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/category
    method:POST

    {
        'name': 'required|string',
        'description': 'string'
    }

    All
    http://127.0.0.1:8000/api/category/
    method:GET

    Show
    http://127.0.0.1:8000/api/category/{id}
    method:GET
    
    Update
    http://127.0.0.1:8000/api/category/{id}
    method:PUT

    {
        'name': 'required|string',
        'description': 'string'
    }
    
    Delete
    http://127.0.0.1:8000/api/category/{id}
    method:DELETE  
</p>

## Orders CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/order
    method:POST
    
    {
        "paymentID": 0,
        "products": [
            {
                "id": required|integer,
                "extras": [
                    {
                        "id": required|integer,
                        "quantity": required|integer
                    }
                ]
            }
        ],
        "addressShipping": "string",
        "nameShipping": "string",
        "phoneShipping": "string"
    }

    Show
    http://127.0.0.1:8000/api/order/{id}
    method:GET

    All
    http://127.0.0.1:8000/api/myOrders/
    method:GET
    
    Update
    http://127.0.0.1:8000/api/order/{id}
    method:PUT
    {
        "paymentID": 0,
        "products": [
            {
                "id": required|integer,
                "extras": {
                    "id": required|integer,
                    "quantity": required|integer
                }
            }
        ],
        "addressShipping": "string",
        "nameShipping": "string",
        "phoneShipping": "string"
    }
    
    Delete
    http://127.0.0.1:8000/api/payment/{id}
    method:DELETE  
</p>


## Payment CRUD
<p>
    Insert
    http://127.0.0.1:8000/api/payment
    method:POST
    
    {
        'name': 'required|string',
        'description': 'string',
        'apiKey': 'required|string',
        'url': 'required|string'
    }

    Show
    http://127.0.0.1:8000/api/payment/{id}
    method:GET

    All
    http://127.0.0.1:8000/api/payment/
    method:GET
    
    Update
    http://127.0.0.1:8000/api/payment/1
    method:PUT
    {
        'name': 'required|string',
        'description': 'string',
        'apiKey': 'required|string',
        'url': 'required|string',
        'active' : required|integer
    }
    
    Delete
    http://127.0.0.1:8000/api/payment/{id}
    method:DELETE  
</p>

## Pay
<p>
    http://127.0.0.1:8000/api/pay
    
    {
        'orderID': required|integer,
        'cardNumber': 'required|string',
        'cardDate': 'required|string',
        'securityCode': 'required|string',
        'cardName': 'required|string',
        'description': 'required|string'
    }
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
