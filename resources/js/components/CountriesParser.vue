<template>
    <div>
        <b-form v-on:submit.prevent="onSubmit" class="mt-2">
            <input type="file" ref="files" style="display:none" @change="uploadFieldChange" />
            <b-row>
                <b-col cols="4">
                    <b-button block variant="success" @click="addFile">Select file</b-button>
                </b-col>
                <b-col cols="4" v-if="this.countries.length">
                    <b-form-select v-model="selectedFormat" :options="fileFormats"></b-form-select>
                </b-col>
                <b-col cols="4" v-if="this.countries.length">
                    <b-button block variant="info" @click="exportFile">Export</b-button>
                </b-col>
            </b-row>
        </b-form>
        <b-list-group class="mt-2">
            <b-list-group-item
                v-for="(country,index) in countries"
                v-bind:key="index"
                :variant="rowInEditMode(index) ? 'warning' : ''"
            >
                <b-row v-if="rowInEditMode(index)">
                    <b-col cols="5">
                        <b-form-input @click="editRow=index" v-model="country.country"></b-form-input>
                    </b-col>
                    <b-col cols="5">
                        <b-form-input @click="editRow=index" v-model="country.capital"></b-form-input>
                    </b-col>
                    <b-col cols="2">
                        <b-button block variant="danger" @click="deleteCountry(index)">X</b-button>
                    </b-col>
                </b-row>
                <b-row v-else>
                    <b-col cols="5" @click="editRow=index">{{country.country}}</b-col>
                    <b-col cols="5" @click="editRow=index">{{country.capital}}</b-col>
                    <b-col cols="2">
                        <b-button block variant="danger" @click="deleteCountry(index)">X</b-button>
                    </b-col>
                </b-row>
            </b-list-group-item>
            <b-list-group-item>
                <b-row>
                    <b-button block @click="addCountry">Add country</b-button>
                </b-row>
            </b-list-group-item>
        </b-list-group>

        <b-alert
            :show="showAlert"
            dismissible
            fade
            variant="danger"
            @dismiss-count-down="countDownChanged"
        >{{alertMessage}}</b-alert>
    </div>
</template>

<script>
import countriesRepository from "../repositories/countries";

export default {
    data() {
        return {
            name: "",
            files: [],
            selectedFormat: "json",
            fileFormats: [
                { value: "json", text: "json" },
                { value: "xml", text: "xml" },
                { value: "csv", text: "csv" }
            ],
            uploading: false,
            maxsize: 2048,
            showAlert: 0,
            alertMessage: "",
            submitted: false,
            countries: [],
            editRow: null
        };
    },
    methods: {
        addCountry() {
            this.countries.push({ country: "", capital: "" });
        },
        deleteCountry(countryIndex) {
            this.countries.splice(countryIndex, 1);
        },
        rowInEditMode(index) {
            return (
                this.editRow == index ||
                this.countries[index].country == "" ||
                this.countries[index].capital == ""
            );
        },
        exportFile() {
            if (!this.validateBeforeExport()) {
                this.showError("Not all fields filled correct");
                return;
            }
            countriesRepository
                .download({
                    countries: this.countries,
                    type: this.selectedFormat
                })
                .then(response => {
                    let preparedData =
                        this.selectedFormat == "json"
                            ? JSON.stringify(response.data)
                            : response.data;
                    let fileURL = window.URL.createObjectURL(
                        new Blob([preparedData])
                    );
                    let fileLink = document.createElement("a");
                    fileLink.href = fileURL;

                    fileLink.setAttribute(
                        "download",
                        "countries." + this.selectedFormat
                    );

                    document.body.appendChild(fileLink);
                    fileLink.click();
                })
                .catch(error => {
                    this.showError(error.response.data.message);
                });
        },
        validateBeforeExport() {
            return (
                this.countries.filter(
                    country => country.country == "" || country.capital == ""
                ).length == 0
            );
        },
        addFile() {
            this.$refs.files.click();
        },
        uploadFieldChange(event) {
            let files = event.target.files || event.dataTransfer.files;
            const size = Math.round(files[0].size / 1024);
            if (size < this.maxsize) {
                this.files = {
                    file: files[0],
                    uploaded: false,
                    title: files[0].name
                };
                this.uploadFile();
            } else {
                this.showError(
                    "File too big, please select a file less than 2mb"
                );
            }
        },
        uploadFile() {
            if (this.uploading) {
                return;
            }
            this.uploading = true;
            let data = new FormData();
            data.append("file", this.files.file);

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            };

            axios
                .post("/api/file/upload", data, config)
                .then(response => {
                    this.uploading = false;
                    this.countries = response.data.data;
                })
                .catch(error => {
                    this.showError(error.response.data.error);
                });
        },
        countDownChanged(dismissCountDown) {
            this.showAlert = dismissCountDown;
        },
        showError(message) {
            this.alertMessage = message;
            this.showAlert = 5;
        }
    }
};
</script>
