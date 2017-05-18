var DOM = React.DOM;

class Register extends React.Component {

  handleInputChange(event) {
    const target = event.target;
    const value = target.value;
    const name = target.name;
    this.setState({
      [name]: value
    });
  }

  render () {
    return (
      DOM.div(null,
        DOM.form({id: "register-form", className: "form-horizontal", action: "/web/site/register", method: "post"},
          DOM.input({type: "hidden", name: "_csrf", value: this.props._csrf}),
          DOM.div({className: "form-group field-customers-name"},
            DOM.label({className: "col-lg-1 control-label", for: "customers-name"}, 'Name'),
            DOM.div({className: "col-lg-3"},
              DOM.input({type: "text", id: "customers-name", className: "form-control", name: "Customers[name]", value: this.props.customer.name})
            ),
            DOM.div({className: "col-lg-8"},
              DOM.div({className: "help-block"})
            )
          ),
          DOM.div({className: "form-group field-customers-email required"},
            DOM.label({className: "col-lg-1 control-label", for: "customers-email"}, 'Email'),
            DOM.div({className: "col-lg-3"},
              DOM.input({type: "text", id: "customers-email", className: "form-control", name: "Customers[email]", value: this.props.customer.email})
            ),
            DOM.div({className: "col-lg-8"},
              DOM.div({className: "help-block"})
            )
          ),
          DOM.div({className: "form-group field-customers-password  required"},
            DOM.label({className: "col-lg-1 control-label", for: "customers-password"}, 'Password'),
            DOM.div({className: "col-lg-3"},
              DOM.input({type: "password", id: "customers-password", className: "form-control", name: "Customers[password]", value: this.props.customer.password})
            ),
            DOM.div({className: "col-lg-8"},
              DOM.div({className: "help-block"})
            )
          ),
          DOM.div({className: "form-group field-customers-password_repeat"},
            DOM.label({className: "col-lg-1 control-label", for: "customers-password_repeat"}, 'Password Repeat'),
            DOM.div({className: "col-lg-3"},
              DOM.input({type: "password", id: "customers-password_repeat", className: "form-control", name: "Customers[password_repeat]", value: this.props.customer.password_repeat})
            ),
            DOM.div({className: "col-lg-8"},
              DOM.div({className: "help-block"})
            )
          ),
          DOM.div({className: "form-group"},
            DOM.div({className: "col-lg-offset-1 col-lg-11"},
              DOM.button({type: "submit",  className: "btn btn-primary", name: 'register-button'}, 'Register'),
              DOM.a({href: '/web/site/login'}, 'Login')
            )
          )
        ))
    );
  }
}