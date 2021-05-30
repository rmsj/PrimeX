# PrimeXConnect API

This project is based on Laravel Lumen framework.
It is a test project for the PrimeXConnect following the requirements received:

## API Routes

- Add, Update, Delete product.
- Add a stock onHand for a product.
- Able to get products and product details.
- Able to pass optional stock parameter in get products and product details API
to get stock onHand summary.
- Able to sort products by stock onHand by both asc and desc order.
- Able to filter products by stock availability.

## Project must also be able to handle bulk create/update of products and stock 

- Able to bulk (5k +) insert/update products into database
- Able to bulk (20k +) insert stock into the database.

## Project Structure

For smaller projects like the one required for this exercise, I tend to keep things simple
and just follow the structure of a normal Laravel/Lumen project. I've chosen to have a more structured approach 
for this small API. I like to work separating all the project features into its own namespace folowing
the idea of `src/<ProjectName>/...` as folder structure.

## Development environment

I've added docker support for the project.
Check the file devenv for the available commands.
There are only 3 services. See the `docker-compose.yml` file to check those.

Running `./devenv start` will create the instance.

To know more about the available commands run
`./devenv help`
Then you can run `./devenv help <command_name>` for a description of what the command does.

### More setup
- Change your `hosts` file on `/etc/hosts` (if you're on mac/linux) or `c:\Windows\System32\Drivers\etc\hosts` if you're 
on windows - and include `127.0.0.1 primex.test`. This will allow navigating to `http://primex.test` and seeing the text
`Exercise API. Built with Lumen (8.2.3) (Laravel Components ^8.0)`

- Rename the file `.env.example` to `.env`

### Postman collection
To test the API use the API collection as per link `https://www.getpostman.com/collections/2a5bc9b355598543239c`

### Populate
To test the API with some available data run the bul product and product stock creation as explained below. 

## Tests
I've added some basic tests that will run inside the docker environment.
To run the test run `./devenv phpunit` from the project root.
You can use whatever `phpunit` filter capability like so `./devenv phpunit --filter BulkAddProductStockCommandTest`
to test only for a specific class.

## Test Commands for Bulk Add/Update

### Add Bulk Products from CSV
To test bulk update of products you can run the command
`./devenv artisan primex:add-products "/var/www/html/tests/data/primex-products-test.csv"`

### Update Bulk Products from CSV
To test bulk update of products you can run the command
`./devenv artisan primex:update-products "/var/www/html/tests/data/primex-products-test.csv"`

### Add Bulk Product Stock from CSV
To test bulk add of product stock entries you can run the command
`./devenv artisan primex:add-product-stock "/var/www/html/tests/data/primex-stock-test.csv"`

All commands above will run the specific Command class inside the docker `php` container 
with the `csv` files provided as part of the requirements and that I have added to the `tests/data` folder.  

## Security
The Security part - authentication/authorization has not been developed as part of this exercise
and the API is open as it is now. 
