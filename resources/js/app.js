require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Unicon from 'vue-unicons';
import { uniUserPlus, uniUserMinus, uniUserCircle, uniUsersAlt, uniBox, uniEdit, uniKeySkeleton, uniLayerGroup, uniLinkH, uniSetting, uniSignout, uniSync, uniThumbsUp, uniThumbsDown, uniTachometerFast, uniClock, uniHeartSign, uniPricetagAlt, uniStoreAlt, uniStore, uniShoppingCart, uniTransaction, uniCreditCardSearch, uniArchive, uniGift, uniLock, uniImage, uniLocationArrow, uniFileExport, uniApps, uniChat, uniCommentAltChartLines, uniBug, uniArrowCircleRight, uniArrowCircleLeft, uniArrowCircleUp, uniArrowCircleDown, uniMicroscope, uniEnvelopeAdd, uniChartLine, uniAnalytics, uniPercentage } from 'vue-unicons/dist/icons'


Unicon.add([uniUserPlus, uniUserMinus, uniUserCircle, uniUsersAlt, uniBox, uniEdit, uniKeySkeleton, uniLayerGroup, uniLinkH, uniSetting, uniSignout, uniSync, uniThumbsUp, uniThumbsDown, uniTachometerFast, uniClock, uniHeartSign, uniPricetagAlt, uniStoreAlt, uniStore, uniShoppingCart, uniTransaction, uniCreditCardSearch, uniArchive, uniGift, uniLock, uniImage, uniLocationArrow, uniFileExport, uniApps, uniChat, uniCommentAltChartLines, uniBug, uniArrowCircleRight, uniArrowCircleLeft, uniArrowCircleUp, uniArrowCircleDown, uniMicroscope, uniEnvelopeAdd, uniChartLine, uniAnalytics, uniPercentage])


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

let permsMixin = {
    userCan(permissions) {
        var allPermissions = this.$page.props.perms;

        if (typeof permissions == 'string') {
            return allPermissions.includes(permissions)
        }

        var hasPermission = false;
        permissions.forEach(function(perm){
            if(!hasPermission && allPermissions.includes(perm)) {
                hasPermission = true;
            }
        });

        return hasPermission;
    }
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(Unicon, {
                fill: '#55c7e7',
                width: 18,
                height: 18
            })
            .mixin({ methods: { route } })
            .mixin({ methods: { ...permsMixin } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#55c7e7' });
