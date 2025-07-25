(({"document": doc, "location": loc,}) => {
    doc.addEventListener("DOMContentLoaded", evt => {
        const form = doc.querySelector(".search-form");
        const queryInput = form.querySelector("*[type='search']");
        const table = form.querySelector(".search-table");
        const caption = table.querySelector("caption");
        const trTpl = form.querySelector("tbody > template");

        form.addEventListener("submit", evt => {
            evt.preventDefault();

            form.querySelectorAll("tbody > tr")
                .forEach(tr => tr.remove());
            caption.textContent = "";

            const url = new URL(form.getAttribute("action"), loc.origin);
            url.search = new URLSearchParams(new FormData(form));

            fetch(url)
                .then(data => data.json())
                .then(data => data.reverse())
                .then(data => data.forEach(item => {
                    caption.textContent = caption.getAttribute("title");

                    const tr = trTpl.content.cloneNode(true);

                    tr.querySelectorAll("*[data-content]")
                        .forEach(node => node.textContent = item[node.dataset.content]);

                    trTpl.after(tr);
                }));

            return false;
        });
    });
})(window);