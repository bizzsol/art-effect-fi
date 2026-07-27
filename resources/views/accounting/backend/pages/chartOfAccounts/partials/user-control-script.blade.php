<script type="text/javascript">
    $(function () {
        const endpoint = '{{ route('accounting.chart-of-accounts.company-users') }}';
        const usersSelect = $('#users');

        function selectedCompanies() {
            return $('.companies:checked').map(function () {
                return this.value;
            }).get();
        }

        usersSelect.select2({
            width: '100%',
            placeholder: usersSelect.data('placeholder'),
            allowClear: true,
            ajax: {
                url: endpoint,
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        q: params.term,
                        companies: selectedCompanies(),
                        selected: usersSelect.val() || []
                    };
                },
                processResults: function (response) {
                    return {results: response.results || []};
                },
                cache: true
            }
        });

        function loadCompanyUsers(companies) {
            if (!companies.length) {
                return;
            }

            $.getJSON(endpoint, {companies: companies}).done(function (response) {
                if (response.status !== 'success') {
                    return;
                }

                const selected = (usersSelect.val() || []).map(String);
                $.each(response.results, function (index, user) {
                    const id = String(user.id);
                    if (!usersSelect.find('option[value="' + id + '"]').length) {
                        usersSelect.append(new Option(user.text, id, false, false));
                    }
                    if (selected.indexOf(id) === -1) {
                        selected.push(id);
                    }
                });

                usersSelect.val(selected).trigger('change');
            });
        }

        // Only the newly ticked company is pulled in, so users already curated on this
        // ledger are never re-added after being deliberately removed.
        $(document).on('change', '.companies', function () {
            if (this.checked) {
                loadCompanyUsers([this.value]);
            }
        });
    });
</script>
