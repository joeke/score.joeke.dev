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
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavBar" aria-controls="mainNavBar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <div class="collapse navbar-collapse" id="mainNavBar">
                    <ul class="navbar-nav main-navbar">
                        <li class="nav-item">
                            <a class="nav-link" :href="route('dashboard')">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :href="route('profile.edit')">Profile</a>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :href="route('logout')" method="post" as="link">Log Out</Link>
                        </li>
                        <li class="ms-auto nav-item">
                            <a class="btn btn-switch-mode" @click="switchTheme">
                                <i v-if="theme === 'light'" class="bi bi-brightness-high"></i>
                                <i v-else class="bi bi-moon"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-xl">
            <header class="my-4" v-if="$slots.header">
                <slot name="header" />
            </header>
        </div>

        <main class="container-xl">
            <slot />
        </main>
    </section>
</template>
