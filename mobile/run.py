from flask import Flask, render_template, request, redirect, url_for
from forms import LoginForm
from flask_login import LoginManager, logout_user, current_user, login_user, login_required
from flask_sqlalchemy import SQLAlchemy
import json, os
import re
from datetime import datetime, date

app = Flask(__name__)
app.config['SECRET_KEY'] = os.getenv("SECRET_KEY")
app.config['SQLALCHEMY_DATABASE_URI'] = os.getenv("SQLALCHEMY_DATABASE_URI")
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

login_manager = LoginManager(app)
login_manager.login_view = "login"

db = SQLAlchemy(app)
from models import RegistrosLegajo, Registros, User

@app.route("/", methods=['GET'])
@login_required
def index():
    editable = isIPEditable()
    reload = False
    registros = RegistrosLegajo.get_by_legajo(current_user.legajo)
    if(editable):
        for registro in registros:
            if not registro.registro_entrada:
                reload = True
                reg = Registros(
                    tipo=1, 
                    computable=1, 
                    asignatura_id=registro.asignatura_id, 
                    usuario_id=registro.usuario_id, 
                    fecha=date.today(), 
                    entrada=datetime.now().strftime("%H:%M:%S") )
                reg.save()
        
        if reload:
            registros = RegistrosLegajo.get_by_legajo(current_user.legajo)
    
    return render_template("registros_form.html", registros=registros, editable=editable)

@app.route("/registros", methods=['POST'])
def registros_save():
    if isIPEditable():
        result = ""
        data = request.get_json()

        reg = Registros.get_by_id(data["id"])
        reg.obs = data["obs"]
        reg.salida = datetime.now().strftime("%H:%M:%S")

        return json.dumps({"result": reg.save(), "salida": datetime.now().strftime("%H:%M:%S")})


@login_manager.user_loader
def load_user(user_id):
    return User.get_by_id(int(user_id))

@app.route('/login', methods=['GET', 'POST'])
def login():
    if current_user.is_authenticated:
        return redirect(url_for('index'))

    form = LoginForm()
    msg = None
    if form.validate_on_submit():
        user = User.get_by_legajo(form.legajo.data)
        if user is None:
            msg = "El número de legajo no existe."
        elif not user.check_password(form.password.data):
            msg = "La contraseña no es válida."
        else:
            login_user(user)
            return redirect( url_for('index') )

    return render_template('login_form.html', form=form, msg=msg)


@app.route('/logout')
def logout():
    logout_user()
    return redirect(url_for('index'))

def isIPEditable():
    return bool(re.search('127.0.0.\d{1,3}|192.168.\d{1,3}.\d{1,3}', request.remote_addr))