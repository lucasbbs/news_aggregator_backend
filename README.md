# The NewsHubConnect Backend

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE.md)

## Description

The news aggregator is a powerful platform that brings together news from popular sources such as the New York Times, The Guardian, and News API. With its user-friendly interface and advanced search capabilities, users can easily explore a vast collection of news articles, tailored to their preferences.

The platform offers two main features:

1. Comprehensive News Search:
Users can perform searches across multiple news sources, including the New York Times, The Guardian, and News API. The search functionality allows users to refine their results by specifying categories and keywords. This empowers users to find the most relevant and up-to-date news articles that align with their interests.

2. Personalized User Experience:
The news aggregator also provides a personalized experience by allowing users to register and save their preferences in the database. By registering an account, users can define their preferred categories, keywords, or topics of interest. Based on their saved information, the platform filters and presents news articles that are most likely to resonate with their individual preferences.

3. RSS Feed: By leveraging the RSS feeds from Wall Street Journal, CBS, BBC, NPR, and New York Times, users can access a comprehensive range of news content conveniently from a single platform.


## Prerequisites

- [Composer](https://getcomposer.org/download/)
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Technologies Used

- Laravel: A popular PHP framework for building robust and scalable web applications.
- Laravel Sail: A lightweight Docker development environment for Laravel applications, providing a consistent and reproducible development environment.
- PHP: The server-side scripting language used by Laravel for processing backend logic.
- MySQL: A popular relational database management system used for storing and retrieving data.
- Eloquent ORM: The database abstraction layer provided by Laravel, simplifying database operations and interactions.
- API Authentication with Sanctum: Laravel Sanctum provides a lightweight and easy-to-use token-based authentication system for securing the API endpoints.
- Composer: A dependency management tool for PHP, used to manage and install the required packages and libraries for the Laravel project.

## Getting Started

These instructions will help you get a copy of the project up and running on your local machine for testing purposes.

### Installation

To set up and run the backend project locally using Docker, follow these steps:
1. Clone the repository from GitHub to your local machine.

```bash
git clone https://github.com/lucasbbs/news_aggregator_challenge_backend.git
```
2. Change directory into the newly created folder.

```bash
cd news_aggregator_challenge_backend
```
3. Install all required dependencies

```bash
./vendor/bin/sail up
```
4. Run migrations for database

```bash
./vendor/bin/sail artisan migrate
```
5. Run database seeder 

```bash
./vendor/bin/sail artisan db:seed
```

## API Reference
### User Endpoints

#### Get User Details
```http
GET /user
```

#### Update Password
```http
PUT /user/password
```

#### Update Name
```http
PUT /user/name
```

#### User Login
```http
POST /auth/login
```

#### User Registration
```http
POST /auth/register
```

#### User Logout
```http
POST /auth/logout
```

### News Endpoints



#### Get Latest News
```http
GET /news/latest
```

#### Get New York Times News
```http
GET /news/nytimes
```
| Query Parameters | Type     | Description           |
| :--------  | :------- | :--------------------------- |
| `keyword`  | `string` | Search news articles containing the specified keyword.|
|`begin_date`| `string format: date` | Filter news articles with a publish date greater than or equal to the specified begin date. |
|`end_date`  | `string format: date` | Filter news articles with a publish date less than or equal to the specified end date. |
| `category` | `string` |Filter news articles by category. |
| `source` | `string` | Filter news articles by source. Possible values: [list of available sources]. | 
| `page`| `number` | Specify the page number of the results. Default: 1. |
| `sort`| `string` | Sort the news articles by a specific criterion. Possible values: [list of available sources]. |
| `search`| `boolean` | Determine if the query will be used to handle a search instead of feed. Default: false. |

#### Get The Guardian News
```http
GET /news/guardian
```

| Query Parameters | Type     | Description           |
| :--------  | :------- | :--------------------------- |
| `keyword`  | `string` | Search news articles containing the specified keyword.|
|`begin_date`| `string format: date` | Filter news articles with a publish date greater than or equal to the specified begin date. |
|`end_date`  | `string format: date` | Filter news articles with a publish date less than or equal to the specified end date. |
| `category` | `string` |Filter news articles by category. |
| `page`| `number` | Specify the page number of the results. Default: 1. |
| `sort`| `string` | Sort the news articles by a specific criterion. Possible values: [list of available sources]. |
| `search`| `boolean` | Determine if the query will be used to handle a search instead of feed. Default: false. |


#### Get NewsAPI News
```http
GET /news/newsapi
```
| Query Parameters | Type     | Description           |
| :--------  | :------- | :--------------------------- |
| `keyword`  | `string` | Search news articles containing the specified keyword.|
|`begin_date`| `string format: date` | Filter news articles with a publish date greater than or equal to the specified begin date. |
|`end_date`  | `string format: date` | Filter news articles with a publish date less than or equal to the specified end date. |
| `page`| `number` | Specify the page number of the results. Default: 1. |
| `sort`| `string` | Sort the news articles by a specific criterion. Possible values: [list of available sources]. |
| `search`| `boolean` | Determine if the query will be used to handle a search instead of feed. Default: false. |


### Sources Endpoints

#### Get All Sources

Retrieve a list of news sources and their categories.
```http
GET /sources
```

#### Get User's Favorite Sources

Retrieve the favorite source categories of the authenticated user.
```http
GET /sources/favorites
```

### Favorites Endpoints

#### Add User's Favorite News

Add a news category to the authenticated user's favorites.
```http
POST /favorites/user-favorites
```

### Tags Endpoints

#### Get All Tags
Retrieve a list of all the tags a user has saved.
```http
GET /tags
```

#### Create a Tag

Create a new tag for the authenticated user.
```http
POST /tags
```

#### Delete a Tag

Delete a tag by its ID.
```http
DELETE /tags/{id}
```

### Settings Endpoints

#### Get User Settings

Retrieve the settings of the authenticated user.
```http
GET /settings
```

#### Update User Settings

Update the settings of the authenticated user.
```http
POST /settings
```
