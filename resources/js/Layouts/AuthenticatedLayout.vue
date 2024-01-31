<script setup>
    import { Link } from '@inertiajs/vue3';
    import { ref } from 'vue';

    let theme = ref(0);

    const switchTheme = () => {
        theme.value =  document.querySelector('html').getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        document.querySelector('html').setAttribute("data-bs-theme", theme.value)
    }
</script>

<template>
    <section>
        <nav class="navbar navbar-expand-lg bg-body-secondary">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavBar" aria-controls="mainNavBar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <a class="btn btn-switch-mode ms-2" @click="switchTheme" title="Switch theme (dark or light)">
                    <i v-if="theme === 'light'" class="bi bi-brightness-high"></i>
                    <i v-else class="bi bi-moon"></i>
                </a>

                <div class="collapse navbar-collapse main-navbar" id="mainNavBar">
                    <div>
                        <Link class="btn" :href="route('dashboard')" as="button">
                            <i class="bi bi-house"></i> Home
                        </Link>

                        <Link class="btn" :href="route('games')" as="button">
                            <i class="bi bi-caret-right-fill"></i> Games
                        </Link>

                        <Link class="btn" :href="route('players')" as="button">
                            <i class="bi bi-people"></i> Players
                        </Link>

                        <Link class="btn btn-sm ms-lg-auto" :href="route('profile.edit')" as="button">
                            <i class="bi bi-person"></i> Profile
                        </Link>

                        <Link class="btn btn-sm" :href="route('logout')" method="post" as="button">
                            <i class="bi bi-box-arrow-right"></i> Log out
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-xl">
            <div v-if="$page.props.flash.error" class="alert alert-danger alert-dismissible my-4">
                {{ $page.props.flash.error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div v-if="$page.props.flash.success" class="alert alert-success alert-dismissible my-4">
                {{ $page.props.flash.success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <header class="my-4" v-if="$slots.header">
                <slot name="header" />
            </header>
        </div>

        <main class="container-xl">
            <slot />
        </main>
    </section>
</template>
