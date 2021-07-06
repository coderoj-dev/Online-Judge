<div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Name ">
                        Contest Name:
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" name="name" placeholder="Contest Name" required
                            value="{{ $contest->name }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Name ">
                        Contest Slug:
                    </label>
                    <div class="col-sm-9">
                        {{url('/')}}/.../<span style="border: 1px solid #dddddd;padding: 0px;font-weight: bold;border-radius: 5px; display: inline-block;min-width: 150px;">{{$contest->slug}}</span><br/>
                        <small class="form-text text-muted">
                        Slug can only be updated when you change contest name.<br/></small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Format ">
                        Contest Format:
                    </label>
                    <div class="col-sm-9">
                        <select class="form-control" name="format">
                            <option value="ioi" {{ $contest->format == 'ioi' ? 'selected' : '' }}>
                                IOI
                            </option>
                            <option value="icpc" {{ $contest->format == 'icpc' ? 'selected' : '' }}>
                                ICPC
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Start Time ">
                        Start Time:
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" name="start" placeholder="Start Time" type="datetime-local"
                            value="{{ $contest->start->format('Y-m-d') . 'T' . $contest->start->format('H:i') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Duration ">
                        Duration:
                    </label>
                    <div class="col-sm-9">
                        <input class="form-control" min="1" name="duration" placeholder="Duration" required="" type="number"
                            value="{{ $contest->duration }}">
                        <small class="form-text text-muted">
                            Contest duration in minutes
                        </small>
                        <br />

                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for=" Contest Publish ">
                        Contest Publish:
                    </label>
                    <div class="col-sm-9">
                        <input {{ $contest->publish ? 'checked' : '' }} style="margin-top: 10px;" name="publish"
                            type="checkbox" value="true">

                    </div>
                </div>