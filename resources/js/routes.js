import Home from './components/Home'
import loginVue from './components/login'
import Payment from "./components/Payment";
export default{
    mode: 'history',

    routes: [
        {
            path: '/home',
            component: Home
        },
        {
            path: '/loginVue',
            component: loginVue
        },
        {
            path: '/paymentStatus',
            name: 'paymentStatus',
            component: Payment
        }
    ]
}
