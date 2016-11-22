import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {LockerService, LockerServiceProvider} from '../../../shared/services/locker';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {PagerService, PagerServiceProvider} from '../../../shared/services/pager';
import {PhotoService} from '../../services/photo.service';
import {PhotoModel} from '../../models/photo-model';

@Component({
    selector: 'photos',
    template: require('./photos-by-tag.component.html'),
})
export class PhotosByTagComponent {
    private queryParams:any = {};
    private pagerService:PagerService;
    private lockerService:LockerService;
    private navigatorService:NavigatorService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(PagerServiceProvider) private pagerServiceProvider:PagerServiceProvider,
                @Inject(LockerServiceProvider) private lockerServiceProvider:LockerServiceProvider,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(PhotoService) private photoService:PhotoService) {
        this.pagerService = this.pagerServiceProvider.getInstance();
        this.lockerService = this.lockerServiceProvider.getInstance();
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page) => this.pagerService.setPage(parseInt(page)));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show) => this.queryParams.show = parseInt(show));

        this.route.params
            .map((params) => params['tag'])
            .subscribe((tag) => {
                this.queryParams.tag = tag;
                this.pagerService = this.pagerServiceProvider.getInstance();
                this.loadPhotos(this.pagerService.getLimitForPage(this.pagerService.getPage()), this.pagerService.getOffset(), this.queryParams.tag)
                    .then((photos:PhotoModel[]) => {
                        this.empty = photos.length === 0;
                    });
            });
    }

    loadMorePhotos() {
        return this.loadPhotos(this.pagerService.getLimit(), this.pagerService.getOffset(), this.queryParams.tag);
    }

    loadPhotos(take:number, skip:number, tag:string) {
        if (this.lockerService.isLocked()) return new Promise((resolve, reject) => reject());
        else this.lockerService.lock();
        return this.photoService.getByTag(take, skip, tag).toPromise().then((photos:PhotoModel[]) => {
            return this.pagerService.appendItems(photos).then((photos:PhotoModel[]) => {
                this.navigatorService.setQueryParam('page', this.pagerService.getPage());
                this.lockerService.unlock();
                return photos;
            });
        }).catch((error:any) => {
            this.lockerService.unlock();
            return error;
        });
    }

    redirectToEditPhoto(photo:PhotoModel) {
        this.navigatorService.navigate(['photo/edit', photo.id]);
    }

    getPhotos() {
        return this.pagerService.getItems();
    }

    showPhotoCallback(photo:PhotoModel) {
        this.navigatorService.setQueryParam('show', photo.id);
    }

    hidePhotoCallback() {
        this.navigatorService.unsetQueryParam('show');
    }

    isEmpty() {
        return !this.lockerService.isLocked() && this.empty === true;
    }
}
