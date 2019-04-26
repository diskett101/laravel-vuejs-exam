import NotFound from './components/NotFound';
import List from './components/List';
import UserFormEdit from './components/UserFormEdit';
import LoginForm from './components/LoginForm';

export default {
    mode: 'history',
    routes: [
        {
            path: "/login",
            component: LoginForm,
            name: 'loginform'
        },
        {
            path: "/user-list",
            component: List,
            name: 'list'
        },
        {
            path: "/user/edit/:user_id",
            component: UserFormEdit,
            name: 'editform'
        }
    ]
}