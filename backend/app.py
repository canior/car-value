import pickle

import json
from flask import Flask, request, jsonify, make_response
from flask_expects_json import expects_json
from jsonschema import ValidationError

app = Flask(__name__)

schema = {
    "type": "object",
    "properties": {
        "make_id": {"type": "integer"},
        "mileage": {"type": "integer"},
        "age": {"type": "integer"}
    },
    "required": ["make_id", "mileage", "age"]
}


@app.route('/', methods=['POST'])
@expects_json(schema)
def index():
    record = json.loads(request.data)
    make_id = str(record['make_id'])
    mileage = int(record['mileage'])
    age = int(record['age'])
    try:
        with open('models/' + make_id + '_model.pkl', 'rb') as f:
            model = pickle.load(f)
    except FileNotFoundError as e:
        return jsonify(dict(message=e.strerror)), 500
    prediction = round(model.predict([[mileage, age]])[0] / 100, 2)
    return jsonify(prediction)


@app.errorhandler(400)
def bad_request(error):
    if isinstance(error.description, ValidationError):
        original_error = error.description
        return make_response(jsonify({'error': original_error.message}), 400)
    return error


if __name__ == "__main__":
    app.run()
