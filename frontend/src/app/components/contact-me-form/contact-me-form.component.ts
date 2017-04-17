import {Component, OnInit} from '@angular/core';
import {ContactMeForm as Form} from './models';
import {MetaTagsService} from '../../../core';
import {NoticesService} from '../../../lib';
import {
    ApiService,
    TitleService,
    LockProcessServiceProvider,
    LockProcessService,
    NavigatorServiceProvider,
    NavigatorService,
} from '../../../shared';

@Component({
    selector: 'contact-me-form',
    templateUrl: 'contact-me-form.component.html',
})
export class ContactMeFormComponent implements OnInit {
    protected form:Form;
    protected navigator:NavigatorService;
    protected lockProcess:LockProcessService;

    constructor(protected api:ApiService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected notices:NoticesService,
                navigatorProvider:NavigatorServiceProvider,
                lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
        this.initForm();
    }

    protected initTitle = ():void => {
        this.title.setTitle('Contact Me');
    };

    protected initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageName());
    };

    protected initForm = ():void => {
        this.setForm(new Form);
    };

    setForm = (form:Form):void => {
        this.form = form;
    };

    getForm = ():Form => {
        return this.form;
    };

    send = ():Promise<any> => {
        return this.lockProcess
            .process(() => this.api.post('/contact_messages', this.getForm()))
            .then((data:any) => {
                this.notices.success('Your message successfully sent.');
                this.navigator.navigate(['/']);
                return data;
            });
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}