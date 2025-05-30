<img src="docs/NistechLogo-grau-150px.png" width="150" alt="Nistech Logo"/>

# Contao Qualli.Id Login
This extension adds a Qualli.Id login button to Contao's frontend and backend. Before/after installation you get and save the `client_id` and `client_secret` in your .env file.
To activate or deactivate the application, the configuration file *config.yaml* must be edited under `config/config.yaml`.
*You need an active license aggreement for Qualli.life.*
This extension is based on Marko Cupics contao-oauth2-client (https://packagist.org/packages/markocupic/contao-oauth2-client). Thanks for your great work.

**Frontend Module**

To display the login button in the frontend, create a member login module and select the *mod_login_qualliid* template.

## Installation

`composer require nistech/contao-qualliid-client`

## Configuration

```yaml
# config/config.yaml
nistech_contao_qualliid_login:
    disable_contao_core_backend_login: false  # Disable original Contao backend login
    enable_csrf_token_check: true

    contao_oauth2_clients:
        qualliid_backend:
            enable_login: true
            client_id: '%env(CONTAO_QUALLI_ID_CLIENT_ID)%'
            client_secret: '%env(CONTAO_QUALLI_ID_CLIENT_SECRET)%'

        qualliid_frontend:
            enable_login: true
            client_id: '%env(CONTAO_QUALLI_ID_CLIENT_ID)%'
            client_secret: '%env(CONTAO_QUALLI_ID_CLIENT_SECRET)%'
```

```
# .env or .env.local
CONTAO_QUALLI_ID_CLIENT_ID=ApiDemoId
CONTAO_QUALLI_ID_CLIENT_SECRET=LongLongAlphaNumericString
```
