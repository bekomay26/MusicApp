[![Maintainability](https://api.codeclimate.com/v1/badges/0090be0efc58b7c5163e/maintainability)](https://codeclimate.com/github/bekomay26/MusicApp/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/0090be0efc58b7c5163e/test_coverage)](https://codeclimate.com/github/bekomay26/MusicApp/test_coverage) 
# Music App

A Music App Store API that will bring an upcoming artiste to the limelight

## Demo
- [Pivotal Tracker](https://www.pivotaltracker.com/n/projects/2352701).
- [Heroku App](https://lms-music.herokuapp.com/).

## Running Locally
- run `composer install`
- run `php artisan migrate`
- signup with the register route(api/v1/register)
- make requests to endpoints

## Features

### User

- Log in /Log out
- Search or go through music genre/categories
- Play/View the songs
- Download a song
- Subscribe to become a premium user
- Pay for a premium account using Stripe
- Generate payment receipt in PDF format

### Artiste
- Create a profile
- Create albums
- Can add tracks to albums


### API Endpoints

- Register - POST api/v1/register
- Login - POST api/v1/login
- Create an album - PUT api/v1/albums
- Add track to album - api/v1/albums/{id}/tracks
- Create a new song - POST api/v1/songs
- View Song - GET api/v1/songs/{id}
- Download song - GET api/v1/songs/download/{id}
- Create genre - POST api/v1/genres
- Subscribe to the premium plan - POST api/v1/subscribes
- Download payment invoice - GET api/v1/subscribes/invoice


## Built with
* [Laravel](https://laravel.com/docs/5.8) - A free and open-source PHP web framework

## Authors
**Folajimi Ogunbadejo**


## License
  [MIT License](https://opensource.org/licenses/MIT)
