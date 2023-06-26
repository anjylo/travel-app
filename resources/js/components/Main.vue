<template>
    <b-container class="p-3" fluid v-if="state.initialize">
        
        <header class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-0 lead">{{ `${country} (${computedCountry.code})` }}</p>
                    <p class="mb-0 text-muted font-italic">{{ computedCountry.capital }}</p>
                </div>

                
                <!-- Weather -->
                <div>
                    <div class="d-flex align-items-center justify-content-center">
                        <b-img fluid :src="weather.condition.icon"></b-img>
                        <p class="m-0 lead align-self-center">{{ `${weather.celcius} &deg;C` }}</p>
                    </div>
                    <p class="m-0 small text-center">{{ weather.condition.text }}</p>
                </div>
            </div>

            <hr>
            
            <nav>
                <b-overlay :show="state.loading">
                    <b-form-select
                        autofocus
                        v-model="country"
                        :options="countries"
                        @change="setCountryData"
                    ></b-form-select>
                </b-overlay>
            </nav>
        </header>

        <main>
            <b-container class="p-0" fluid>
                <b-skeleton-wrapper :loading="state.loading">
                    <template #loading>
                        <b-card>
                            <b-skeleton-img></b-skeleton-img>
                            <br>
                            <b-skeleton></b-skeleton>
                        </b-card>
                    </template>
                    
                    <!-- place -->
                    <Place :data="places"></Place>
                </b-skeleton-wrapper>
            </b-container>
            
        </main>
        
    </b-container>
</template>

<script>
    import Place from './Place.vue'

    export default {
        components: {
            Place
        },
        data() {
            return {
                state: {
                    loading: false,
                    initialize: false
                },
                countries: [
                    'Brunei',
                    'Cambodia',
                    'Indonesia',
                    'Laos',
                    'Malaysia',
                    'Myanmar',
                    'Philippines',
                    'Singapore',
                    'Thailand',
                    'Vietnam'
                ],
                country: '',
                weather: {},
                places: []
            }
        },
        computed: {
            computedCountry() {
                const data = {
                    'Brunei': {
                        code: 'BN',
                        capital: 'Bandar Seri Begawan'
                    },
                    'Cambodia': {
                        code: 'KH',
                        capital: 'Phnom Penh'
                    },
                    'Indonesia': {
                        code: 'ID',
                        capital: 'Jakarta'
                    },
                    'Laos': {
                        code: 'LA',
                        capital: 'Vientiane'
                    },
                    'Malaysia': {
                        code: 'MY',
                        capital: 'Kuala Lumpur'
                    },
                    'Myanmar': {
                        code: 'MM',
                        capital: 'Naypyidaw'
                    },
                    'Philippines': {
                        code: 'PH',
                        capital: 'Manila'
                    },
                    'Singapore': {
                        code: 'SG',
                        capital: 'Singapore'
                    },
                    'Thailand': {
                        code: 'TH',
                        capital: 'Bangkok'
                    },
                    'Vietnam': {
                        code: 'VN',
                        capital: 'Hanoi'
                    }
                };

                return data[this.country]
            }
        },
        methods: {
            toggleModal() {
                this.state.modal = !this.state.modal
            },
            async getPlaces(data) {
                return await axios.get(`/api/place/${data.toLowerCase()}`)
                                .then(response => response.data)
            },
            async getWeather(data) {
                return await axios.get(`/api/weather/${data.toLowerCase()}`)
                                .then(response => response.data)
            },
            async setCountryData() {
                this.state.loading = true

                try {
                    this.weather = await this.getWeather(this.country)
                    this.places = await this.getPlaces(this.computedCountry.capital)
                } catch (error) {
                    console.log(`An error has occured! ${error}`)
                } finally {
                    this.state.loading = false

                    if (!this.state.initialize) {
                        this.state.initialize = true
                    }
                }
            }
        },
        created() {
            this.country = 'Philippines'
            this.setCountryData()
        }
    }
</script>

<style lang="scss" scoped>

</style>
