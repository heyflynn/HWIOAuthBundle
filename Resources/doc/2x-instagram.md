Step 2x: Setup Instagram
========================
First you will have to [register your application](http://instagram.com/developer/clients/manage/)
on Instagram.

Next configure a resource owner of type `instagram` with appropriate `client_id`, `client_secret`
and `scope` (optional). Refer to the [Instagram documentation](http://instagram.com/developer/authentication/)
for the available scopes.

``` yaml
# app/config/config.yml

hwi_oauth:
    resource_owners:
        any_name:
            type:                instagram
            client_id:           <client_id>
            client_secret:       <client_secret>
            scope:               ""
```

When you're done. Continue by configuring the security layer or go back to
setup more resource owners.

- [Step 2: Configuring resource owners (Facebook, GitHub, Google, Windows Live and others](2-configuring_resource_owners.md)
- [Step 3: Configuring the security layer](3-configuring_the_security_layer.md).
