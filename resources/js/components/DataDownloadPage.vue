<template>

    <div>
        <h2>Data Download Page</h2>
    </div>

</template>

<script>
import {computed, h, onMounted, ref, watchEffect} from "vue";

export default {
    name: "DataDownloadPage",
    components: {
    },
    props: {
    },
    setup(props) {

        onMounted(() => {
            //getVarietyList(null, null)
            //getPropertyList()
        })

        // Present initial list as table
        const columns = ref([
            {
                title: "Código variedad ",
                key: "id",
            },
            {
                key: "common_name",
                title: "Nombre común"
            }
        ]);

        watchEffect(onInvalidate => {
            /*
            let token = axios.CancelToken.source()

            getVarietyList(search.value, flowerFilters.value, fruitFilters.value, tuberFilters.value, sproutFilters.value, token.token)

            onInvalidate(() => {
                console.log('stopping');
                token.cancel()
            })
            */
        })


        // Click on a table row and get the full variety props:

        watchEffect(onInvalidateGet => {
            /*
            let token = axios.CancelToken.source()
            if (selectedVarietyId.value && selectedVarietyId.value !== "") {
                selectedVariety.value = {};
                getVariety(selectedVarietyId.value, token.token)
            }

            onInvalidateGet(() => {
                console.log('stopping getting variety');
                token.cancel();
            })
            */
        })


        /*
        return {
            varietyList,
            getVarietyList,
            propertyList,
            getPropertyList,
            columns,
            search,
            flowerProps,
            flowerFilters,
            fruitProps,
            fruitFilters,
            tuberProps,
            tuberFilters,
            sproutProps,
            sproutFilters,
            rowProps,
            selectedVarietyId,
            selectedVariety,
            getVariety,
            showModal,
            showImg,
            currentImg,
            currentImgPreview,
            setCurrentImg,
            showOnlyReviewed,
        };
        */
    },
}
</script>

<style>

</style>
