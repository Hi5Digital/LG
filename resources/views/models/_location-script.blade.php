<script>
    const provinces = {!! json_encode($provinces) !!};
    const towns = {!! json_encode($towns) !!};
    const mcountry = '{{ optional($model_location)->cid }}';
    const mprovince = '{{ optional($model_location)->pid }}';
    const mtown = '{{ optional($model_location)->lid }}';
    const oldcountry = '{{ old('country') ?? '' }}';
    const oldprovince = '{{ old('province') ?? '' }}';
    const oldtown = '{{ old('town') ?? '' }}';

    const countryField = $('#country');
    const provinceField = $('#province');
    const townField = $('#town');

    countryField.on('change', () => {
        provinceField.html('');
        townField.html('');
        const cid = countryField.val();
        provinceField.append('<option value="">Select Province</option>');
        townField.append('<option value="">Select Town</option>');
        if (!cid) {
            return;
        }
        provinces.filter(cur => parseInt(cur['cid']) === parseInt(cid)).forEach(cur => {
            provinceField.append(`<option value="${cur['id']}">${cur['name']}</option>`);
        });
    });

    provinceField.on('change', () => {
        townField.html('');
        const pid = provinceField.val();
        townField.append('<option value="">Select Town</option>');
        if (!pid) {
            return;
        }
        towns.filter(cur => parseInt(cur['pid']) === parseInt(pid)).forEach(cur => {
            townField.append(`<option value="${cur['id']}">${cur['name']}</option>`);
        });
    });
</script>
