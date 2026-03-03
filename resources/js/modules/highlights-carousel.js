/**
 * Highlights Carousel - Alpine.js component
 *
 * Netflix-style translateX carousel with arrow navigation.
 * Usage: x-data="highlightsCarousel()"
 */
export default function highlightsCarousel() {
    return {
        currentIndex: 0,
        cardWidth: 0,
        totalCards: 0,
        visibleCards: 3,

        initScroll() {
            const c = this.$refs.highlightsContainer;
            const card = c.querySelector('[data-card]');
            if (!card) return;

            this.cardWidth = card.offsetWidth + 24; // card width + gap
            this.totalCards = c.children.length;

            this.updatePosition();
        },

        scrollHighlights(direction) {
            const maxIndex = this.totalCards - this.visibleCards;

            this.currentIndex += direction;

            if (this.currentIndex < 0) this.currentIndex = 0;
            if (this.currentIndex > maxIndex) this.currentIndex = maxIndex;

            this.updatePosition();
        },

        updatePosition() {
            const c = this.$refs.highlightsContainer;
            c.style.transform = `translateX(-${this.currentIndex * this.cardWidth}px)`;
        },

        get canScrollLeft() {
            return this.currentIndex > 0;
        },

        get canScrollRight() {
            return this.currentIndex < this.totalCards - (this.visibleCards + 1);
        },
    };
}
