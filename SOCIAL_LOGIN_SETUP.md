# Social Login Setup

This project supports social login with Google and Facebook. Follow these steps to set it up:

## Prerequisites

1. Make sure you have installed Laravel Socialite:
   ```
   composer require laravel/socialite
   ```

2. Run the migration to add social login fields to the pembelis table:
   ```
   php artisan migrate
   ```

## Setting up OAuth Credentials

### Google OAuth Setup

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Navigate to "APIs & Services" > "Credentials"
4. Click "Create Credentials" > "OAuth client ID"
5. Select "Web application" as the application type
6. Add your app's URL to the "Authorized JavaScript origins"
7. Add your callback URL to the "Authorized redirect URIs" (e.g., `http://your-app.com/auth/google/callback`)
8. Click "Create" and note your Client ID and Client Secret

### Facebook OAuth Setup

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app or select an existing one
3. Add the "Facebook Login" product to your app
4. In the settings for Facebook Login, add your callback URL to the "Valid OAuth Redirect URIs" (e.g., `http://your-app.com/auth/facebook/callback`)
5. From the app dashboard, note your App ID and App Secret

## Environment Configuration

Add the following to your `.env` file:

```
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://your-app.com/auth/google/callback

FACEBOOK_CLIENT_ID=your-facebook-client-id
FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
FACEBOOK_REDIRECT_URI=http://your-app.com/auth/facebook/callback
```

## Usage

The login and register pages now include buttons to log in with Google or Facebook. Clicking these buttons will redirect users to the respective OAuth provider for authentication.

When a user logs in with a social provider for the first time, a new account will be created with their information. If a user with the same email already exists, the social login information will be linked to their existing account. 