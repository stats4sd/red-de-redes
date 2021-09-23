<template>
    <div class="row justify-content-center">
        <div class="col-10 h-100">
            <div class="container">
                <ul class="progressbar">
                    <li
                        v-for="step in steps"
                        :key="step.id"
                        :class="{
                            'pb_done' : currentStep > step.id,
                            'pb_active' : currentStep == step.id
                        }"
                    >
                        <h3>Paso {{ step.id }}</h3>
                        {{ step.title }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: {
            currentStep: {
                type: Number,
                default: 1,
            },
            steps: {
                type: Array,
                default () {
                    return [];
                }
            }
        },

        data () {
            return {
            }
        }

    }
</script>