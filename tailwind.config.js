module.exports = {
  theme: {
    extend: {
        backgroundColor: {
            page: 'var(--page-background-color)',
            card: 'var(--card-background-color)',
            button: 'var(--button-background-color)',
            header: 'var(--header-background-color)',
        },
        colors: {
            default: 'var(--text-default-color)',
        }
    },
    boxShadow: {
        default: '0 0 5px 0 rgba(0, 0, 0, .08)',
    },
    minHeight: {
        '150': '150px'
    },
  },
  variants: {},
  plugins: []
}
