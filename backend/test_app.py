from app import app
import unittest
import json


class AppTestCase(unittest.TestCase):

    def test_prediction_successfully(self):
        tester = app.test_client(self)
        request_data = {
            "make_id": 1,
            "mileage": 60000,
            "age": 3
        }
        response = tester.post("/", data=json.dumps(request_data), headers={'Content-Type': 'application/json'})
        assert response.status_code == 200

    def test_invalid_request(self):
        tester = app.test_client(self)
        request_data = {
            "make_id": 'invalid',
            "mileage": 'invalid',
            "age": 'invalid'
        }
        response = tester.post("/", data=json.dumps(request_data), headers={'Content-Type': 'application/json'})
        assert response.status_code == 400

    def test_model_not_found_request(self):
        tester = app.test_client(self)
        request_data = {
            "make_id": 99999,
            "mileage": 60000,
            "age": 3
        }
        response = tester.post("/", data=json.dumps(request_data), headers={'Content-Type': 'application/json'})
        assert response.status_code == 500


if __name__ == "__main__":
    unittest.main()
