(({"document": doc, "location": loc,}) => doc.addEventListener("DOMContentLoaded", evt => {
    const form = doc.querySelector(".search-form");

    const [queryInput, table,] = ["*[type='search']", ".search-table",]
        .map(cssSelector => form.querySelector(cssSelector));

    const [caption, trTpl,] = ["caption", "tbody > template",]
        .map(cssSelector => table.querySelector(cssSelector));

    form.addEventListener("submit", evt => {
        evt.preventDefault();

        form.querySelectorAll("tbody > tr").forEach(tr => tr.remove());

        const url = new URL(form.getAttribute("action"), loc.origin);
        url.search = new URLSearchParams(new FormData(form));

        fetch(url)
            .then(data => data.json())
            .then(data => data.reverse())
            .then(data => data.forEach(item => {
                const tr = trTpl.content.cloneNode(true);

                tr.querySelectorAll("*[data-content]")
                    .forEach(node => node.textContent = item[node.dataset.content]);

                trTpl.after(tr);
            }));

        return false;
    });
}))(window);