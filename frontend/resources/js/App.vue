<template>
    <div>
        <h3 class="text-center">Predict</h3>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="prediction">
                    <div class="form-group">
                        <label>Year</label>
                        <input type="text" class="form-control" v-model="predictRequest.year">
                    </div>
                    <div class="form-group">
                        <label>Make</label>
                        <input type="text" class="form-control" v-model="predictRequest.make">
                    </div>
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" class="form-control" v-model="predictRequest.model">
                    </div>
                    <div class="form-group">
                        <label>Mileage</label>
                        <input type="text" class="form-control" v-model="predictRequest.mileage">
                    </div>
                    <button type="submit" class="btn btn-primary">Predict</button>
                </form>

                <div v-show="predictionValue != null">
                    <b>{{ predictionValue }}</b>
                </div>

                <table v-show="predictionValue != null" class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Detail</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="car in cars" :key="car.id">
                        <td>{{ car.id }}</td>
                        <td>{{ car.make }}</td>
                        <td>{{ car.model }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            predictRequest: {},
            cars: [],
            predictionValue: null,
        }
    },
    created() {
    },
    methods: {
        prediction() {
            return axios
                .post(`http://localhost:8000/api/car/prediction`, {
                    'year': 2011,
                    'make': 'bmw',
                    'model': 'x3',
                })
                .then((res) => {
                    console.log(res.data);
                    this.predictionValue = res.data.prediction;
                    this.cars = res.data.data;
                });
        }
    }
}
</script>
