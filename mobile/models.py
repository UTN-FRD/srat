import bcrypt
from flask_login import UserMixin
from werkzeug.security import generate_password_hash
from run import db

class RegistrosLegajo(db.Model):
    usuario_id = db.Column(db.Integer, primary_key=True)
    legajo = db.Column(db.Integer)
    asignatura_id = db.Column(db.Integer, primary_key=True)
    carrera = db.Column(db.String(50), nullable=False)
    materia = db.Column(db.String(50), nullable=False)
    registro_id = db.Column(db.Integer, nullable=True)
    registro_entrada = db.Column(db.Time, nullable=True)
    registro_salida = db.Column(db.Time, nullable=True)
    registro_obs = db.Column(db.String(255), nullable=True)
    
    def __repr__(self):
        return f'<RegistrosLegajo {self.legajo} - {self.carrera} - {self.materia}>'

    @staticmethod
    def get_by_legajo(legajo):
        return RegistrosLegajo.query.filter_by(legajo=legajo).all()


class Registros(db.Model):
    id = db.Column(db.Integer,nullable=False, primary_key=True)
    tipo = db.Column(db.Integer,nullable=False, default=1)
    computable = db.Column(db.Integer,nullable=False, default=1)
    asignatura_id = db.Column(db.Integer, nullable=False)
    usuario_id = db.Column(db.Integer, nullable=False)
    fecha = db.Column(db.Date, nullable=False)
    entrada = db.Column(db.Time, nullable=True)
    salida = db.Column(db.Time, nullable=True)
    obs = db.Column(db.Text, nullable=True)
    
    def __repr__(self):
        return f'<Registros {self.usuario_id} - {self.asignatura_id} - {self.fecha} - {self.entrada} - {self.salida}>'

    def save(self):
        result = f"ID: {self.id}"
        if not self.id:
            db.session.add(self)

        saved = False
        count = 0
        while not saved:
            db.session.commit()
            saved = True
        return saved


    @staticmethod
    def get_by_id(id):
        return Registros.query.filter_by(id=id).first()

class User(db.Model, UserMixin):

    __tablename__ = 'usuarios'

    id = db.Column(db.Integer, primary_key=True)
    nombre = db.Column(db.String(40), nullable=False)
    apellido = db.Column(db.String(25), nullable=False)
    legajo = db.Column(db.Integer, nullable=False)
    password = db.Column(db.String(128), nullable=False)
    admin = db.Column(db.Boolean, default=False)

    def __init__(self, name, email):
        self.name = name
        self.email = email

    def __repr__(self):
        return f'<User {self.legajo}>'

    def set_password(self, password):
        self.password = generate_password_hash(password)

    def check_password(self, password):
        return bcrypt.checkpw(bytes(password, "utf-8"),bytes(self.password, "utf-8"))

    def save(self):
        if not self.id:
            db.session.add(self)
        db.session.commit()

    @staticmethod
    def get_by_id(id):
        return User.query.get(id)

    @staticmethod
    def get_by_legajo(legajo):
        return User.query.filter_by(legajo=legajo).first()
