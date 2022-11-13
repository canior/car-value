import { mount, shallowMount, flushPromises } from "@vue/test-utils";
import App from "../../js/App.vue";
import axios from 'axios';

jest.mock('axios');
const mockedAxios = axios as jest.Mocked<typeof axios>;

const prediction = 1000;
const cars = [
    { "id": "1", "title": "book1", "subtitle": "hello1", "year": 1938},
    { "id": "1", "title": "book1", "subtitle": "hello1", "year": 1938},
    { "id": "1", "title": "book1", "subtitle": "hello1", "year": 1938}
];
const fakeResponse = Promise.resolve({"data": {"data": cars, "prediction": prediction}});


describe("BookIndex.vue", () => {

    beforeEach(() => {
    })

    it("correctly predict with correct data", async () => {

        mockedAxios.get.mockReturnValueOnce(fakeResponse);

        const wrapper = shallowMount(App, {
            global: {
                plugins: [],
            }
        } as any);

        expect(axios.post).toBeCalledWith("/api/car/prediction");

        await flushPromises();
        console.log(wrapper.vm);
        expect(wrapper.vm.cars.length).toBe(3);
    });

});
