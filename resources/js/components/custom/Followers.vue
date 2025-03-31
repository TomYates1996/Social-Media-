<template>
    <div class="followers-con">
        <span class="count" v-on:click="toggleFollowers">{{ followerCount }} Follower</span>
        <ul class="followers" v-if="followersOpen">
            <p>Follower</p>
            <li class="follower" v-for="follower in followers" :key="follower.id">
                <img :src="follower.profile_picture" alt="profile picture">
                {{ follower.username }}
            </li>
            <p class="none-to-show" v-if="followers.length < 1">No Followers</p>
        </ul>
    </div>
    <div class="followers-con">
        <span class="count" v-on:click="toggleFollowing">{{ followingCount }} Following</span>
        <ul class="followers" v-if="followingOpen">
            <p>Following</p>
            <li class="follower" v-for="following in following" :key="following.id">
                <img :src="following.profile_picture" alt="profile picture">
                {{ following.username }}
            </li>
            <p class="none-to-show" v-if="following.length < 1">No Followers</p>
        </ul>
    </div>
</template>

<script>

export default {
    components: {
    },
    data() {
        return {
            followingOpen: false,
            followersOpen: false,
        }
    },
    props: {
        followers: Array,
        following: Array,
        followerCount: Number,
        followingCount: Number,
    },
    methods: {
        toggleFollowing() {
            this.followersOpen = false;
            this.followingOpen = !this.followingOpen;
        },
        toggleFollowers() {
            this.followersOpen = !this.followersOpen;
            this.followingOpen = false;
        }
    },
}
</script>

<style scoped>
.count {
    cursor: pointer;
}
.followers {
    position: fixed;
    height: calc(100vh - 127px);
    width: 100%;
    left: 0px;
    top: 127px;
    padding: 20px;
    gap: 10px;
    display: flex;
    flex-direction: column;
    background-color: var(--primary-colour);
    z-index: 10;
    overflow-y: scroll;
    padding-bottom: 80px;
    .follower {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 18px;
        img {
            height: 50px;
            width: 50px;
            border-radius: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
        }
    }
}
</style>