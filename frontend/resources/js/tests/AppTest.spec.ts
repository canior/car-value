import { shallowMount } from "@vue/test-utils";
import App from "../App.vue";
import axios from "axios";

jest.mock("axios");

describe("App.vue", () => {
    it("correctly show empty predict form", async () => {
        const wrapper = shallowMount(App, {} as any);

        expect(wrapper.find('#year').text()).toBe("");
        expect(wrapper.find('#make').text()).toBe("");
        expect(wrapper.find('#model').text()).toBe("");
        expect(wrapper.find('#mileage').text()).toBe("");
        expect(wrapper.find('#prediction_btn').text()).toBe("Predict");
        expect(wrapper.find('#prediction_result').exists()).toBe(false);
        expect(wrapper.find('#prediction_data').exists()).toBe(false);
    });

    it("correctly handle predict form validation", async () => {
        const mockedAxios = axios as jest.Mocked<typeof axios>;
        mockedAxios.post.mockResolvedValue({
            data: [],
        });

        const wrapper = shallowMount(App, {} as any);

        await wrapper.find('#prediction_btn').trigger('click');
        expect(wrapper.find('#errors').exists()).toBe(true);

        await wrapper.find('#year').setValue(2022);
        await wrapper.find('#prediction_btn').trigger('click');
        expect(wrapper.find('#errors').exists()).toBe(true);

        await wrapper.find('#make').setValue('bmw');
        await wrapper.find('#prediction_btn').trigger('click');
        expect(wrapper.find('#errors').exists()).toBe(true);

        await wrapper.find('#model').setValue('x5');
        await wrapper.find('#prediction_btn').trigger('click');
        expect(wrapper.find('#errors').exists()).toBe(false);
    });

    it("correctly handle server 422 error", async () => {
        const serverError = 'Service validation error';
        const mockedAxios = axios as jest.Mocked<typeof axios>;
        mockedAxios.post.mockRejectedValue({
            response: {
                status: 422,
                data: {
                    message: serverError
                }
            }
        });

        const wrapper = shallowMount(App, {} as any);

        await wrapper.find('#year').setValue(1000);
        await wrapper.find('#make').setValue('bmw');
        await wrapper.find('#model').setValue('x5');
        await wrapper.find('#prediction_btn').trigger('click');
        expect(wrapper.find('#errors').exists()).toBe(true);
        expect(wrapper.find('#errors').text()).toContain(serverError);
    });

    it("correctly handle server other errors", async () => {
        const mockedAxios = axios as jest.Mocked<typeof axios>;
        mockedAxios.post.mockRejectedValue({});

        const wrapper = shallowMount(App, {} as any);

        await wrapper.find('#year').setValue(1000);
        await wrapper.find('#make').setValue('bmw');
        await wrapper.find('#model').setValue('x5');
        await wrapper.find('#prediction_btn').trigger('click');
        expect(wrapper.find('#errors').exists()).toBe(true);
        expect(wrapper.find('#errors').text()).toContain('Something wrong in server, please connect admin');
    });


    it("correctly get predict form result", async () => {
        const prediction = '10000';
        const cars = {
            "data": [
                {
                    "id": 1,
                    "make": "Kia",
                    "model": "FORTE",
                    "year": "2015",
                    "price": "$10,000",
                    "mileage": "10,000 km",
                },
                {
                    "id": 2,
                    "make": "GMC",
                    "model": "Sierra 1500",
                    "year": "2017",
                    "price": "$20,000",
                    "mileage": "20,000 km",
                },
                {
                    "id": 3,
                    "make": "Dodge",
                    "model": "Ram Pickup 1500",
                    "year": "1999",
                    "price": "$30,000",
                    "mileage": "30,000 km",
                },
            ],
            "prediction": prediction
        };

        const mockedAxios = axios as jest.Mocked<typeof axios>;
        mockedAxios.post.mockResolvedValue({
            data: cars,
        });

        const wrapper = shallowMount(App, {} as any);


        await wrapper.find('#year').setValue(2022);
        await wrapper.find('#make').setValue('bmw');
        await wrapper.find('#model').setValue('x5');
        await wrapper.find('#prediction_btn').trigger('click');
        for (let car of cars.data) {
            expect(wrapper.find('#prediction_data').text()).toContain(car.make);
            expect(wrapper.find('#prediction_data').text()).toContain(car.model);
            expect(wrapper.find('#prediction_data').text()).toContain(car.year.toString());
            expect(wrapper.find('#prediction_data').text()).toContain(car.price.toString());
            expect(wrapper.find('#prediction_data').text()).toContain(car.mileage.toString());
        }

        expect(wrapper.find('#prediction_result').text()).toContain(prediction);
    });

});
