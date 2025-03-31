<template>
    <div class="profile-banner">
        <h4 class="username">{{ user.username }}</h4>
        <div class="inner-middle">
            <div class="name-img">
                <img class="profile-pic" :src="user.profile_picture" alt="">
                <Followers :following="following" :followingCount="followingCount" :followerCount="processCount" :followers="followersProcessed"/>
            </div>
            <h1 class="name" v-if="user.name.length > 0">{{ user.name }}'s Profile</h1>
        </div>
            <button
                v-if="$page.props.auth.user.username !== user.username"
                @click="toggleFollow"
                :class="followed ? 'bg-gray-500' : '' "
                >
                {{ followed ? 'Unfollow' : 'Follow' }}
            </button>
       
    </div>
</template>

<script>
import Followers from './Followers.vue';
import axios from 'axios';


export default {
    components: {
        Followers
    },
    props: {
        user: Object,
        count: Number,
        followers: Array,
        isFollowing: Boolean,
        following: Array,
        followingCount: Number,
    },
    data() {
        return {
            followed: this.isFollowing,
            followersProcessed: [],
            processCount: 0,
        }
    },
    created() {
        this.storedPosts = this.posts;
        this.followersProcessed = this.followers;
        this.processCount = this.count;
    },
    methods: {
        toggleFollow() {
            if (this.followed) {
                // Unfollow user
                axios.post(`/profile/${this.user.username}/unfollow`)
                .then(() => {
                    this.followed = false;
                    this.followersProcessed = this.followers.filter(user => user.id !== this.$page.props.auth.user.id);
                    this.processCount = this.processCount - 1;
                    
                })
                .catch(error => {
                    console.error('Error unfollowing user:', error);
                });
            } else {
                // Follow user
                axios.post(`/profile/${this.user.username}/follow`)
                .then(() => {
                    this.followed = true;
                    this.followersProcessed.push(this.$page.props.auth.user);
                    this.processCount = this.processCount + 1;
                })
                .catch(error => {
                    console.error('Error following user:', error);
                });
            }
        },
    }
}
</script>

<style scoped>
    .profile-banner {
        display: flex;
        flex-direction: column;
        width: 100%;
        justify-content: flex-start;
        align-items: center;
        background-color: var(--primary-colour);
        border-bottom: 1px solid var(--link-colour);
        padding: 20px;
        .username {
            font-size: 26px;
            text-align: left;
        }
        .inner-middle {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }
        .name-img {
            display: flex;
            align-items: center;
            gap: 10px;
            .profile-pic {
                height: 50px;
                width: 50px;
                border-radius: 100%;
            }
        }
    }
</style>