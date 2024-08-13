from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField, PasswordField
from wtforms.validators import DataRequired

class LoginForm(FlaskForm):
    legajo = StringField('Legajo', validators=[DataRequired()], render_kw={'class':"form-control", 'id':"exampleInputEmail1", 'aria-describedby':"emailHelp"})
    password = PasswordField('Password', validators=[DataRequired()], render_kw={'class':'form-control', 'id':"exampleInputPassword1"})
    submit = SubmitField('Ingresar', render_kw={'class':'btn btn-primary'})

