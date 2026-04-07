import template from "./ProductFinderStepMarker.html";

export default {
    template,
    props: {
        stepNumber: {
            type: Number,
            required: true,
        },
        stepsLabels:{
            type: Array,
            required: true,
        },
    }
}

