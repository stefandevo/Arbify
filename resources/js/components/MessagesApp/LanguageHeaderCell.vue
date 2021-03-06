<template>
    <th>
        <div class="d-flex align-items-center">
            <img v-if="language.flagUrl" :src="language.flagUrl" alt="" class="country-flag">

            {{ language.displayName }}

            <span class="translation-progress-bg">
                <span :class="progressClasses" :style="{ width: stats.percent }"></span>
            </span>
            <small class="ml-auto messages-statistics">
                <span :class="statsClasses">
                    {{ stats.translated }}/{{ stats.all }}
                    ({{ stats.percent }})
                </span>
            </small>
        </div>
    </th>
</template>

<script>
    export default {
        props: ['languageId'],
        computed: {
            language() {
                return this.$store.getters.languageById(this.languageId);
            },
            stats() {
                const language = this.language;

                const all = this.$store.getters.messages.reduce((acc, message) => {
                    if (message.type === 'plural') {
                        return acc + language.pluralForms.length;
                    } else if (message.type === 'gender') {
                        return acc + language.genderForms.length;
                    }

                    return acc + 1;
                }, 0);

                const translated = this.$store.getters.messageValues.reduce((acc, messageValue) => {
                    if (messageValue.languageId !== language.id) {
                        return acc;
                    }

                    return acc + 1;
                }, 0);

                const percent = Math.round((translated / Math.max(all, 1) + Number.EPSILON) * 100) + '%';

                return {
                    all,
                    translated,
                    percent,
                };
            },
            progressClasses() {
                return [
                    'translation-progress',
                    this.stats.percent === '100%' ? 'bg-success' : 'bg-info',
                ];
            },
            statsClasses() {
                if (this.stats.percent === '100%') {
                    return 'text-success';
                } else if (this.stats.percent === '0%') {
                    return 'text-danger';
                }

                return 'text-info';
            }
        },
    };
</script>
