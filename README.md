# ORO Product Review Bundle

This bundle adds reviews to products.

## Requirements

- oro/commerce-enterprise v4.1.*

## Installation and Usage
- Install via Composer:
```
composer require smile/product-review-bundle
```
- Purge Oro cache:
```
php bin/console cache:clear --env=prod
```
- Update oro platform: 
```
bin/console oro:platform:update --force
```
- For configure ReCAPTCHA - update your config.yml:
```
ewz_recaptcha:
    public_key: 'here_is_your_public_key'
    private_key: 'here_is_your_private_key'
```

## Enable ReCAPTCHA:

- Login to Oro Admin
- Navigate to System Configuration => Integrations => ReCAPTCHA
- Configure the ReCAPTCHA widget and enable "Protect Product Review Form"
