window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false,
    auth: {
        headers: {
            Authorization: "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDQ1Y2U0NDM0OTJhOGFlNTI0NmNiMGY4MjdkNjA4NTQyYjg2NTc2MjdhYzY1NDE3YjFlYTMxZTlhOWU1ZGE1ZWE4OTJhM2U2MjEyZTQ2YjEiLCJpYXQiOjE2MjM1MTIyMDQuMTU1ODU2LCJuYmYiOjE2MjM1MTIyMDQuMTU1OTE1LCJleHAiOjE2NTUwNDgyMDQuMTQ2NTgzLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.AeEVRmG1ei6RtFFlExrO_MAn7bOyrmyO2NI3Mk-Znb0ORyNoDz8_3nAp48ZbS1t6Tyl-tuNQ0TqJeNpeHxc1UhFMuLPXToAuVeKC4rsUA2IFwDLLm-hUAsH2TP32deQfemHbnkCuW41aKJieTeFAyx1NsJDDRIfwNiN1l6-R2jqDwDn11P8dnsxo8Dcq8WEK5PT6WK8aXseYdGAPwjbduGrGdR8gxigiXoSNC3CHmzQ79kBgSyin9MpU3T6c0snQwsxv6yyOtzp8h-VWmX8CQKafWvlWHHEjzHkUd12aucgJn2hZOH-ueK9GSnsvM4yxzW5ML-MuvABd1X_txzholjKKuee1CDyejXRFZheMPDC-IHQfE5wZckJDDG_j01txxmUwesSGKpk3QgTROjjGk5IpdrN1MJtJi5TuuUXyWULqdN9wfgOmgpQY4nmtrR7kRrBpU1GcjJwcclJ1aZII1N7HW2BgdJuoTb20UmMinCBxAPBV1O-nTsSA8xF9Aoip5POj1hNDXc90gehyGA-KWnFTA0O9QAEeW19Zd0FePsak869RoXy0n2dltdhxtuJC-hwj91BWChVMGNcqNVhv6fPhNaSxLc9Rf6_IyvJKeRsFIqvb4g7IzMSKFi8uU7XqDuIs97lP4I6qSG1EORI_VGEckZKTZfw88FMvaRBtSe8",
            Accept: "application/json",
        },
    },

});

window.$ = require('jquery');