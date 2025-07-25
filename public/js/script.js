(({"document": doc, "location": loc,}) => doc.addEventListener("DOMContentLoaded", evt => {
    const form = doc.querySelector(".search-form");
    const table = form.querySelector(form.dataset.output);
    const trTpl = table.querySelector(table.dataset.output);
    const formUrl = new URL(form.getAttribute("action"), loc.origin);
    const formMsg = table.querySelector(table.dataset.msg);
    const formGetMsg = (key, error) => error ? [formMsg.dataset[key], error,].join(": ") : formMsg.dataset[key];
    const formSetMsg = (key, error) => formMsg.textContent = formGetMsg(key, error);

    form.addEventListener("submit", evt => {
        evt.preventDefault();

        table.querySelectorAll(table.dataset.data).forEach(node => node.remove());
        formUrl.search = new URLSearchParams(new FormData(form));
        formSetMsg("pending");

        fetch(formUrl)
            .then(data => data.json())
            .then(data => {
                if (data.length) return data;

                throw formGetMsg("notfound");
            })
            .then(data => data.reverse())
            .then(data => data.forEach(item => {
                const tr = trTpl.content.cloneNode(true);

                tr.querySelectorAll("*[data-content]")
                    .forEach(node => node.textContent = item[node.dataset.content]);

                trTpl.after(tr);

                formSetMsg("done");
            }))
            .catch(error => formSetMsg("error", error));

        return false;
    });
}))(window);