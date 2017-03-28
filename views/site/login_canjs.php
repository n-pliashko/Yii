<div id="app"></div>
<script  type="text/mustache"  can-autorender id="app-template">
<div class="site-login">
    <h1>{{customer.title}}</h1>
    <p>Please fill out the following fields to login:</p>
    <form id="login-form" class="form-horizontal" data-toggle="validator">
        <input type="hidden" name="_csrf" value="{{#_csrf}}{{_csrf}}{{else}}{{app.request.getCsrfToken()}}{{/_csrf}}" />
        <div class="form-group field-loginform-email">
            <label class="col-lg-1 control-label" for="loginform-email">Email</label>
            <div class="col-lg-3">
                <input type="email" id="loginform-email" class="form-control" name="LoginForm[email]"
                autofocus="" aria-required="true" aria-invalid="true" value="{{model.email}}"
                data-error="Email address is invalid" required>
            </div>
            <div class="col-lg-8"><div class="help-block help-block-error with-errors"></div></div>
        </div>
        <div class="form-group field-loginform-password required">
            <label class="col-lg-1 control-label" for="loginform-password">Password</label>
            <div class="col-lg-3">
                <input type="password" id="loginform-password"
                class="form-control" name="LoginForm[password]" aria-required="true"
                value="{{model.password}}"
                data-error="Password must contain at least 8 or more characters"
                data-minlength="8" required>
            </div>
            <div class="col-lg-8"><div class="help-block help-block-error with-errors"></div></div>
        </div>
        <div class="form-group field-loginform-rememberme">
            <div class="col-lg-offset-1 col-lg-3"><input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" {{#model.rememberMe}}checked{{/model.rememberMe}}>
                <label for="loginform-rememberme">Remember Me</label>
            </div>
            <div class="col-lg-8"><div class="help-block help-block-error with-errors"></div></div>
        </div>
        <div class="form-group">
        <div class="col-lg-offset-1 col-lg-3 has-error"><div class="help-block help-block-error">{{#error}}{{error}}{{/error}}</div></div>
            <div class="col-lg-offset-1 col-lg-11">
                <button type="submit" class="btn btn-primary" id="login-button" name="login-button">Login</button>
                <a href="/web/site/register">Register</a>
            </div>
        </div>
    </form>
</div>
</script>
<script>
    var Login = can.Control.extend({
        "#login-button click" : function(el, e) {
            if (el.hasClass('disabled')) return false;
            console.log('submit');
            values = can.deparam(this.element.serialize());
            can.ajax({
                type: 'POST',
                dataType: 'JSON',
                data: values,
                url: '/web/site/login_canjs/',
                success: function(document) {
                    if (document.status == 'success' &&  document.redirect) {
                        window.location.href = document.redirect;
                    }
                    var data = new can.Map(document);
                    $('#app').html(can.view('app-template', data));
                    var control = new Login('#login-form');
                }
            });
            return false;
        }
    });

    $('#app').html(can.view('app-template', {}));
    var control = new Login('#login-form');
   can.route.ready();
</script>
