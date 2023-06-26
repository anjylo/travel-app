<template>
    <div>
        <div v-for="(item, key) in data" :key="key">
            <b-card
                :img-src="item.img"
                :img-alt="item.name"
                :title="item.name"
                img-top
                class="mb-3"
                :sub-title="`(${item.categories.join(', ')})`"
            >
                <b-card-text>
                    <div class="w-100 d-flex justify-content-end align-items-center">
                        <div v-b-tooltip.hover title="View reviews" class="mx-3">
                            <b-icon icon="chat-quote" class="h5 text-secondary" @click="setReviews(item.id)"></b-icon>
                        </div>
                        <div v-b-tooltip.hover title="View on map">
                            <b-icon icon="geo-alt" class="h5 text-secondary" @click="viewOnMap(item.coordinates)"></b-icon>
                        </div>
                    </div>
                </b-card-text>
            </b-card>    
        </div>

        <!-- reviews -->
        <b-modal
            size="lg"
            scrollable 
            hide-footer
            no-close-on-esc
            no-close-on-backdrop
            title="Popular Reviews"
            @hidden="reviews = {}"
            v-model="state.modal"
        >
            <b-skeleton-wrapper :loading="state.loading">
                <template #loading>
                    <b-skeleton width="25%"></b-skeleton>
                    <b-skeleton></b-skeleton>
                </template>

                <div v-if="reviews.length > 0">
                    <div v-for="(item, key) in reviews" :key="key">
                        <p class="text-muted mb-0"><small>@{{ item.created_at }}</small></p>
                        <p>{{ item.text }}</p>
                    </div>
                </div>
                
                <div v-else>
                    <p class="lead text-muted text-center">No reviews available.</p>
                </div>
            </b-skeleton-wrapper>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                state: {
                    modal: false,
                    loading: false
                },
                reviews: {},
            }
        },
        methods: {
            viewOnMap({latitude, longitude}) {
                window.open(
                    `https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}`, 
                    '_blank'
                )
            },
            async getReviews(id) {
                return await axios.get(`/api/place/reviews/${id}`)
                                .then(response => response.data)
            },
            async setReviews(id) {
                this.state.loading = true
                this.state.modal = true

                try {
                    this.reviews = await this.getReviews(id)
                } catch (error) {
                    console.log(`An error has occured! ${error}`)
                } finally {
                    this.state.loading = false
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>