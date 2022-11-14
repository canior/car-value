<template>
    <div class="container">
        <h3 class="text-center">Car Value Prediction</h3>
        <div id="errors" v-if="errors.length" class="alert alert-danger">
            <b>Please correct the following error(s):</b>
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </div>
        <div class="row">
            <div id="prediction_form">
                <div class="form-group">
                    <label>Year</label>
                    <input type="text" id="year" class="form-control" v-model="predictRequest.year">
                </div>
                <div class="form-group">
                    <label>Make</label>
                    <input type="text" id="make" class="form-control" v-model="predictRequest.make">
                </div>
                <div class="form-group">
                    <label>Model</label>
                    <input type="text" id="model" class="form-control" v-model="predictRequest.model">
                </div>
                <div class="form-group">
                    <label>Mileage</label>
                    <input type="text" id="mileage" class="form-control" v-model="predictRequest.mileage">
                </div>
                <div class="form-group">
                    <button @click="submit" id="prediction_btn" class="btn btn-primary">Predict</button>
                </div>
            </div>

            <div v-if="predictionValue !== null">
                <div class="alert alert-info" id="prediction_result" >
                    <b v-if="predictionValue !== '$0'">Market Value Prediction: {{ predictionValue }}</b>
                    <b v-else>Market Value Prediction Failed</b>
                </div>

                <table id="prediction_data" v-if="predictionValue !== '$0'" class="table">
                    <thead>
                    <tr>
                        <th colspan="4">
                            Sample listings that were used to compute the market value
                        </th>
                    </tr>
                    <tr>
                        <th>Vehicle</th>
                        <th>Price</th>
                        <th>Mileage</th>
                        <th>Location</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="car in cars" :key="car.id">
                        <td>{{ car.year }} {{ car.make }} {{ car.model }}</td>
                        <td>{{ car.price }}</td>
                        <td>{{ car.mileage }}</td>
                        <td>{{ car.location }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            errors: [],
            predictRequest: {},
            cars: [],
            predictionValue: null,
        }
    },
    methods: {
        checkValidPredictRequest() {
            this.errors = [];
            if (!this.predictRequest.year) {
                this.errors.push("Year is required.");
            }
            if (!this.predictRequest.model) {
                this.errors.push("Model is required.");
            }
            if (!this.predictRequest.make) {
                this.errors.push("Make is required.");
            }
        },

        resetData() {
            this.cars = [];
            this.predictionValue = null;
        },

        submit() {
            this.checkValidPredictRequest();

            if (this.errors.length > 0) {
                this.resetData();
                return false;
            }

            return axios
                .post(process.env.MIX_ENDPOINT_URL + `/api/car/prediction`, this.predictRequest)
                .then((res) => {
                    this.predictionValue = res.data.prediction;
                    this.cars = res.data.data;
                })
                .catch((error) => {
                        this.errors = [];
                        if (error.response && error.response.status && error.response.status === 422) {
                            this.errors.push(error.response.data.message);
                            this.resetData();
                        } else {
                            this.errors.push('Something wrong in server, please connect admin');
                            this.resetData();
                        }
                    }
                )
        }
    }
}
</script>
