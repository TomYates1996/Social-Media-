<template>
    <div class="options-bar">
        <Link 
            v-if="$page.props.auth.user"
            href="/dashboard"
            method="get"
            class="option"
            :class="page === 0 ? 'active' : ''"
        >
            <font-awesome-icon :icon="['fas', 'house']" />
        </Link>
        <Link 
        v-if="$page.props.auth.user"
        href="/explore"
        method="get"
        class="option"
        :class="page === 1 ? 'active' : ''"

        >
        <font-awesome-icon :icon="['fas', 'binoculars']" />
    </Link>
        <Link 
        v-if="$page.props.auth.user"
        href="/post/create"
        method="get"
        class="option"
        :class="page === 4 ? 'active' : ''"

        >
        <font-awesome-icon :icon="['fas', 'square-plus']" />
    </Link>
    <Link 
        v-if="$page.props.auth.user"
        :href="'/profile/' + $page.props.auth.user.username"
        method="get"
        class="option"
        :class="page === 2 ? 'active' : ''"
    >
        <font-awesome-icon :icon="['fas', 'user']" />
    </Link>
    <Link 
        v-if="$page.props.auth.user.username"
        href="/settings"
        method="get"
        class="option"
        :class="page === 3 ? 'active' : ''"
    >
        <font-awesome-icon :icon="['fas', 'user-gear']" />
    </Link>
        <!-- <Link 
            v-if="$page.props.auth.user"
            href="/logout"
            method="post"
            class="option"
        >
            <font-awesome-icon :icon="['fas', 'right-from-bracket']" />
        </Link> -->
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';


export default {
    props: {
        page: Number,
        user: Object,
    },
    components: {
        Link
    },
    methods: {
    }
}
</script>

<style>
.options-bar {
    position: fixed;
    bottom: 0px;
    width: 100%;
    background-color: var(--primary-colour);
    border-top: 1px solid var(--link-colour);
    color: var(--white);
    padding: 4px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    z-index: 10;
    .option {
        padding: 0px 12px;
    }
    .option.active {
        background-color: var(--link-colour);
    }
}
</style>