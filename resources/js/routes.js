import Home from './components/Home'
import loginVue from './components/login'

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
        }
    ]
}